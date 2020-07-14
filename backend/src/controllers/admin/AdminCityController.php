<?php 
    namespace App\controllers\admin;

use App\Entity\City;
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
// routes regarding Admin city controller
// /admin/city      : description : posting a specefic city                       methods: POST
// /admin/cities    : description : getting the whole available cities              methods:GET
// /admin/city/{id} : description : modifying, deleting or getting a specific city by id    methods: GET , PATCH, DELETE

class AdminCityController extends AbstractController { 

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
        private function jsonToCityObject($body , EntityManager $em) :City { 
                $city = new City(); 
                $city->setName($body["name_"]);  
                $wilaya = $em->getRepository(Wilaya::class)->findOneBy(['id' =>$body["wilaya"]["id"]]);
                $city->setWilaya($wilaya); 
               return $city; 

        }

             /** @Route("/admin/city" , name="post_city" , methods={"POST"}) */
    public function  postCity( Request $request ) : Response { 
        try { 
            $this->em= $this->getDoctrine()->getManager();

            $body =  json_decode( $request ->getContent() , true ); 
            $city= $this->jsonToCityObject($body , $this->em); 
            $this->em->persist($city); 
            $this->em->flush(); 
            $cityJson= $this->hateoas->serialize($city , 'json'); 
            return new  Response(  $cityJson , Response::HTTP_CREATED);

        }catch(Exception $e) { 
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

        }

    }

         /** @Route("/admin/city/{id}" , name="get_city" , methods={"GET"}) */
         public function  getCityById(int $id , Request $request ):Response 
         { 
              try {  
                $this->em= $this->getDoctrine()->getManager(); 
                 $city = $this->em->getRepository(City::class) ->findOneBy(['id' =>$id]) ; 
                 $cityJson= $this->hateoas->serialize($city , 'json'); 
                 return new  Response(  $cityJson , Response::HTTP_OK);

              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);


              }

        }
    
         /** @Route("/admin/city/{id}" , name="patch_city" , methods={"PATCH"}) */
         public function   patchCityById(int $id , Request $request ):Response 
         { 
              try {  
                $this->em= $this->getDoctrine()->getManager(); 
                 $city = $this->em->getRepository(City::class) ->findOneBy(['id' =>$id]) ; 
                 $body= json_decode( $request->getContent() , true) ; 
                 // updating according to the available attribute
                 if(isset($body['name_'])) { $city->setName($body['name_']); }
                 if(isset($body['wilaya']["id"])) { $city->setName($body['wilaya']["id"]); 
                } 
                $this->em->flush(); 
                 $cityJson= $this->hateoas->serialize($city , 'json'); 
                 return new  Response(  $cityJson , Response::HTTP_OK);

              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);


              }

        }
    
     

         /** @Route("/admin/city/{id}" , name="delete_city" , methods={"DELETE"}) */
         public function  deleteCityById(int $id , Request $request ):Response 
         { 
              try {  
                 $this->em= $this->getDoctrine()->getManager(); 
                 $city = $this->em->getRepository(City::class) ->findOneBy(['id' =>$id]) ; 
                 $this->em->remove($city);
                 $this->em->flush();
                 return new JsonResponse(['message' => "deleted successfully"], Response::HTTP_OK);
                 
              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

               
              }

        }


         /** @Route("/admin/cities" , name="get_cities" , methods={"GET"}) */
         public function  getCities(  RouteSettings $rs ):Response 
         { 
              try {  
                 $this->em= $this->getDoctrine()->getManager(); 
                 $cities = $this->em->getRepository(City::class) ->findAll();  
                 $citiesJson= $this->hateoas
                 ->serialize( $rs->pagination($cities , 'get_cities') , 'json');
                 return new  Response(  $citiesJson , Response::HTTP_OK);
                  
              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

              }

        }
   

   




    }
  