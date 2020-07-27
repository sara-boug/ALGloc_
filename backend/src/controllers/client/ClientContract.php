<?php 
namespace App\controllers\client;

use App\Entity\Client;
use App\service\ContractService;
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
        private function getCurrentClient(EntityManager $em , $controller):Client
        { 
             $user= $controller->getUser(); //the user is of type client according to the token 
             $client =$em->getRepository(Client::class)->findOneBy(['email' =>$user->getemail()]); 
             return $client; 
        }
        /** @Route("/client/contract" , name="post_contract_client" , methods={"POST"}) */
        public function postContract(Request $request  , JWTTokenManagerInterface $jwt , 
          ContractService $contractService)  { 
            try{  
                 $em= $this->getDoctrine()->getManager();
                $client =$this ->getCurrentClient($em , $this); 
                $body= json_decode( $request->getContent() , true); 
                $contract = $contractService->JsonToContractObject( $body , $em , $client );
                $em->persist($contract) ; 
                $em->flush();

                $contractJson= $this->hateoas->serialize($contract , 'json'); 
                return new Response( $contractJson, Response::HTTP_OK , ["Content-type" => "application\json"]);
     
             }catch( Exception $e ) { 
                   return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
             } 

        } 
       
        public function getcontract(){ 

        }

        
    }




?> 