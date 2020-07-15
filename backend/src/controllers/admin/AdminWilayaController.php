<?php 
    namespace App\controllers\admin;

use App\Entity\Wilaya;
use Doctrine\ORM\EntityManager;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\CallableUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;       
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route; 
use Exception;
use  Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\HttpFoundation\Response;
use App\service\RouteSettings; 
// routes regarding Admin wilaya controller
// /admin/wilaya      : description : posting a specefic  wilaya                       methods: POST
// /admin/wilayas     : description : getting the whole available wilayas              methods:GET
// /admin/wilaya/{id} : description : modifying, deleting or getting a specific wilaya by id    methods: GET , PATCH, DELETE

class AdminWilayaController extends AbstractController { 

    private $hateoas; 
    private $em ; 
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
        private function jsonToWilayaObject($body ) :Wilaya { 
                $wilaya = new  Wilaya(); 
                $wilaya->setName($body["name_"]);  
                return $wilaya; 

        }

    /** @Route("/admin/wilaya" , name="post_wilaya" , methods={"POST"}) */
    public function  postwilaya( Request $request ) : Response { 
        try { 
            $this->em= $this->getDoctrine()->getManager();

            $body =  json_decode( $request ->getContent() , true ); 
            $wilaya= $this->jsonToWilayaObject($body , $this->em); 
            $this->em->persist($wilaya); 
            $this->em->flush(); 
            $wilayaJson= $this->hateoas->serialize($wilaya , 'json'); 
            return new  Response(  $wilayaJson , Response::HTTP_CREATED, ["Content-type" => "application\json"]);

        }catch(Exception $e) { 
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

        }

    }

         /** @Route("/admin/wilaya/{id}" , name="get_wilaya" , methods={"GET"}) */
         public function  getWilayaById(int $id , Request $request ):Response 
         { 
              try {  
                $this->em= $this->getDoctrine()->getManager(); 
                 $wilaya = $this->em->getRepository(Wilaya::class) ->findOneBy(['id' =>$id]) ; 
                 $wilayaJson= $this->hateoas->serialize($wilaya , 'json'); 
                 return new  Response(  $wilayaJson , Response::HTTP_OK, ["Content-type" => "application\json"]);

              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);


              }

        }
    
         /** @Route("/admin/wilaya/{id}" , name="patch_wilaya" , methods={"PATCH"}) */
         public function   patchWilayaById(int $id , Request $request ):Response 
         { 
              try {  
                $this->em= $this->getDoctrine()->getManager(); 
                 $wilaya = $this->em->getRepository(Wilaya::class) ->findOneBy(['id' =>$id]) ; 
                 $body= json_decode( $request->getContent() , true) ; 
                 // updating according to the available attribute
                 if(isset($body['name_'])) { $wilaya->setName($body['name_']); }
                 $this->em->flush(); 
                 $wilayaJson= $this->hateoas->serialize($wilaya , 'json'); 
                 return new  Response(  $wilayaJson , Response::HTTP_OK, ["Content-type" => "application\json"]);

              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);


              }

        }
    
     

         /** @Route("/admin/wilaya/{id}" , name="delete_wilaya" , methods={"DELETE"}) */
         public function  deletewilayaById(int $id , Request $request ):Response 
         { 
              try {  
                 $this->em= $this->getDoctrine()->getManager(); 
                 $wilaya = $this->em->getRepository(Wilaya::class) ->findOneBy(['id' =>$id]) ; 
                 $this->em->remove($wilaya);
                 $this->em->flush();
                 return new JsonResponse(['message' => "deleted successfully"], Response::HTTP_OK, ["Content-type" => "application\json"]);
                 
              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

               
              }

        }


         /** @Route("/admin/wilayas" , name="get_wilayas" , methods={"GET"}) */
         public function  getwilayas(  RouteSettings $rs ):Response 
         { 
              try {  
                 $this->em= $this->getDoctrine()->getManager(); 
                 $wilayas = $this->em->getRepository(wilaya::class) ->findAll();  
                 $wilayasJson= $this->hateoas
                 ->serialize( $rs->pagination($wilayas , 'get_wilayas') , 'json');
                 return new  Response(  $wilayasJson , Response::HTTP_OK, ["Content-type" => "application\json"]);
                  
              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

              }

        }
   

   




    }
  