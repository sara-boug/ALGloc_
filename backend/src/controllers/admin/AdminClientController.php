<?php
    namespace App\controllers\admin; 
        use App\Entity\Client;
use App\Repository\ClientRepository;
use App\service\RouteSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\Routing\RouterInterface; 
        use Hateoas\HateoasBuilder; 
        use Hateoas\UrlGenerator\CallableUrlGenerator; 
        use Symfony\Component\HttpFoundation\Response; 
        use Symfony\Component\HttpFoundation\Request;
        use Exception;
        use Symfony\Component\Routing\Annotation\Route; 
        use Symfony\Component\HttpFoundation\JsonResponse; 

        class AdminClientController  extends AbstractController{ 
        //  routes regarding the client Controller
        // /admin/clients         :  description: iterating through all the clients    methods: get
        // /admin/client/{id}     :  description: modifying specific client             methods : Delete , get
        // /admin/clients/city/id :  description: get clients by city name             methods:  GET

            
            private $hateoas; 
            public function __construct( RouterInterface $router)
            {
                $this->hateoas = HateoasBuilder::create() 
                ->setUrlGenerator(
                    null , 
                    new CallableUrlGenerator(function($route , $parameters , $absolute) use ($router) { 
                        return $router->generate($route , $parameters , RouterInterface::ABSOLUTE_URL); 
                    })
                )->build(); 
            }
                /**
                 * @Route("/admin/client/{id}"  , name="get_client" , methods ={"GET"})
                 */
                public function getClient(  int $id,  Request $request):  Response
                { 
                    try { 
                        $em= $this->getDoctrine()->getManager() ; 
                        $client= $em->getRepository(Client::class)->findOneBy(['id' =>$id]); 
                        $clientJson =  $this->hateoas->serialize($client , 'json'); 
                        return new Response($clientJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                    }catch(Exception $e){ 
                        return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                    }
                
                }
                /** @Route("/admin/clients"  , name="get_clients" , methods ={"GET"})     */
                public function getClients(   RouteSettings $setting):  Response
                { 
                    try {
                        $em= $this->getDoctrine()->getManager() ; 
                        $clients= $em->getRepository(Client::class)->findAll(); 
 
                        $clientJson =  $this->hateoas->serialize(
                        $setting->pagination($clients,"get_clients") , 'json'); 
                        return new Response($clientJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

            
                    }catch(Exception $e){ 
                        return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                    }

                }

           /** @Route("/admin/client/{id}"  , name="delete_client" , methods ={"DELETE"})     */
              public function delete(  int $id , ClientRepository $clientRepo):  Response
             {  
              try{
                $clientRepo->delete($id); 
 
                return new JsonResponse(["message" => "deleted successfully"], Response::HTTP_OK, ["Content-type" => "application\json"]);

              }catch(Exception $e){ 
                  dd($e);
                return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
            }


             }
        }


?> 