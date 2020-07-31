<?php 
namespace App\controllers\client;

use App\Entity\Client;
use App\Entity\Contract_;
use App\Repository\Contract_Repository;
use App\service\ContractService;
use App\service\RouteSettings;
use Doctrine\ORM\EntityManager;
use Exception;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\RouterInterface; 
    use Hateoas\HateoasBuilder; 
    use Hateoas\UrlGenerator\CallableUrlGenerator;
    use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface; 
    use Symfony\Component\Routing\Annotation\Route ; 
    use Symfony\Component\HttpFoundation\Response; 
    use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\service\Setting; 

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
                $em->persist($contract) ; 
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
         , RouteSettings $RoutSetting ){ 
              try { 
                  $em = $this->getDoctrine()->getManager(); 
                  $client = $RoutSetting->getCurrentClient($em, $this->getUser()) ; 
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

        
    }




?> 