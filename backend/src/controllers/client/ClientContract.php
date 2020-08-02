<?php 
namespace App\controllers\client;

    use App\Entity\Contract_;
    use App\Repository\Contract_Repository;
    use App\service\ContractService;
    use App\service\RouteSettings;
    use DateTime;
    use Exception;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\RouterInterface; 
    use Hateoas\HateoasBuilder; 
    use Hateoas\UrlGenerator\CallableUrlGenerator;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface; 
    use Symfony\Component\Routing\Annotation\Route ; 
    use Symfony\Component\HttpFoundation\Response; 
    use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ClientContract extends AbstractController{ 
    private $hateoas; 
        public function __construct(  RouterInterface $router)
        {
            $this->hateoas= HateoasBuilder::create()
            ->setUrlGenerator(
                null , 
                new CallableUrlGenerator(function($route , array $parameter , $absolute) use ( $router ){ 
                        return $router->generate($route , $parameter , UrlGeneratorInterface::ABSOLUTE_URL);
                })
            )->build(); 

        }

        /** @Route("/client/contract" , name="post_contract_client" , methods={"POST"}) */
        public function postContract(Request $request   , 
          ContractService $contractService , RouteSettings $routeSetting)  { 
            try{  
                 $em= $this->getDoctrine()->getManager();
                $client =$routeSetting ->getCurrentClient($em , $this->getUser()); 
                $body= json_decode( $request->getContent() , true); 
                $contract = $contractService->JsonToContractObject( $body , $em , $client );
                if ($contract->getDeparture() > $contract->getArrival()) {
                    return new JsonResponse(['message' => "departure can not be after the arrival"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }
                // checking the availability consists of selecting all the contracts related to the vehicle
                // then checking whether the period choosen by the client doesn't interfer with another contract 
                $contracts = $em->getRepository(Contract_::class)->findBy(
                    ['vehicle' => $contract->getVehicle()->getid()]);
                 
                $availability = $contractService->contractCheckDate(
                                $contract->getDeparture() ,  $contract->getArrival() , $contracts); 
                if ($availability) {
                    return new JsonResponse(['message' => "Vehicle Already Allocated at this period"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }
                 $em->persist($contract) ; 
                 $em->flush();
                 // generating the contract automatically with client
                 $invoice=  $contractService->generateInvoice($contract->getDeparture() 
                 , $contract->getArrival() , $contract); 
                 $em->persist($invoice); 
                 $em->flush(); 
                 $contract->setLink("get_contract_client"); 
                 $contract->getclient() ->setLink("get_contract_client"); // setting up the link for the client related to the contract
                 $contractJson= $this->hateoas->serialize($contract , 'json');
              
                return new Response( $contractJson, Response::HTTP_OK , ["Content-type" => "application\json"]);
     
             }catch( Exception $e ) { 

                    return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
             } 

        } 
         /** @Route("/client/contracts",  name="get_contracts_client" , methods={"GET"}) */
        public function getcontracts(RouteSettings $routeSettings){ 

            try{ 
                $em=$this->getDoctrine()->getManager() ;
                $client= $routeSettings->getCurrentClient($em , $this->getUser()); 
                $clientContracts= $em->getRepository(Contract_::class)
                ->findBy(['client' => $client->getid()]); 
                // setting up the contract link for each elements in the contracts array 
                foreach($clientContracts as $contract){ 
                    $contract->setLink("get_contract_client"); 
                    $contract->getclient() ->setLink("get_contract_client"); // setting up the link for the client related to the contract
                }
               $contractsJson= $this->hateoas->serialize(
               $routeSettings->pagination($clientContracts ,"get_contracts_client"  ),
               'json');
               return new Response( $contractsJson, Response::HTTP_OK , ["Content-type" => "application\json"]);

            }catch(Exception $e) { 
                return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }

         /** @Route("/client/contract/{id}",  name="get_contract_client" , methods={"GET"}) */
        public function getContract(int $id , Contract_Repository $contractRepo 
         , RouteSettings $routSetting ){ 
              try { 
                  $em = $this->getDoctrine()->getManager(); 
                  $client = $routSetting->getCurrentClient($em, $this->getUser()) ; 
                  $clientContract = $contractRepo->selectContractByIdClientId($id , $client->getid()); 
                   
                  if(! $clientContract ) { 
                    return new JsonResponse(["message" => "Not Found"], Response::HTTP_NOT_FOUND, ["Content-type" => "application\json"]);

                  }
                  $clientContract->setLink("get_contract_client"); 
  
                  $clientContract->getclient() ->setLink("get_contract_client"); // setting up the link for the client related to the contract
 

                  $clientContractJson = $this->hateoas->serialize(  $clientContract,'json'); 
                  return new  Response( $clientContractJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                  // the client is only allowed to access the contracts that belong to them 
                }catch(Exception $e){ 
                return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

              }
        } 

         /** @Route("/client/contract/{id}",  name="patch_contract_client" , methods={"PATCH"}) */
         public function patchContract(int $id, Request $request 
          , Contract_Repository $contractRepo  ,  ContractService $contractService , RouteSettings $routeSettings) { 
           try {  
               $em=$this->getDoctrine()->getManager(); 
               $body= json_decode( $request->getContent() ,true );
               // getting the authorized client
               $client= $routeSettings->getCurrentClient($em, $this->getUser()); 
               $contract=  $em->getRepository(Contract_::class)->findOneBy(['id'=> $id , 'client' =>$client ]);
               if( !$contract ) {
                return new JsonResponse(["message" => "Not Found"], Response::HTTP_NOT_FOUND, ["Content-type" => "application\json"]);
               }
                $contract= $contractService -> patchContractArrival($contract , $em,  $contractRepo, $id , $body);
                // updating the cancelled attribute
                // contract can only be cancelled  at minimum of 30 days 
                if(isset($body['cancelled'])) {
                     $diff=   $contract->getDeparture()->diff( new DateTime('now'));  
                     $days= $diff->format('%d') + $diff->format('%m') ; // the number of days between the current day and the departure day
                      if($days<30) { 
                        return new JsonResponse(["error" => "can not cancel the date "], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                      }
                      $contract->setcancelled(true); 
                    }
                $em->flush(); 
               $contract->setLink("get_contract_client"); 
               $contract->getclient() ->setLink("get_contract_client"); // setting up the link for the client related to the contract
               $contractJson = $this->hateoas->serialize($contract, 'json');
               return new Response($contractJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

           }catch(Exception $e){ 
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

           }


         }


        



        

 
        
    }




?> 