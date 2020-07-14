<?php 
    namespace App\controllers\admin;

use App\Entity\brand;
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
// routes regarding Admin brand controller
// /admin/brand      : description : posting a specefic  brand                       methods: POST
// /admin/brands     : description : getting the whole available brands              methods:GET
// /admin/brand/{id} : description : modifying, deleting or getting a specific brand by id    methods: GET , PATCH, DELETE

class AdminBrandController extends AbstractController { 

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
        private function jsonToBrandObject($body ) :brand { 
                $brand = new  brand(); 
                $brand->setName($body["name_"]);  
                return $brand; 
        }

    /** @Route("/admin/brand" , name="post_brand" , methods={"POST"}) */
    public function  postBrand( Request $request ) : Response { 
        try { 
            $this->em= $this->getDoctrine()->getManager();

            $body =  json_decode( $request ->getContent() , true ); 
            $brand= $this->jsonToBrandObject($body , $this->em); 
            $this->em->persist($brand); 
            $this->em->flush(); 
             $brandJson= $this->hateoas->serialize($brand , 'json'); 
            return new  Response(  $brandJson , Response::HTTP_CREATED);

        }catch(Exception $e) { 
          dd($e); 
             return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

        }

    }

         /** @Route( "/admin/brand/{id}" , name="get_brand" , methods={"GET"}) */
         public function  getBrandById(int $id  ):Response 
         { 
              try {  
                $this->em= $this->getDoctrine()->getManager(); 
                 $brand = $this->em->getRepository(Brand::class) ->findOneBy(['id' =>$id]) ; 
                 $brandJson= $this->hateoas->serialize($brand , 'json'); 
                 return new  Response(  $brandJson , Response::HTTP_OK);

              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);


              }

        }
    
         /** @Route("/admin/brand/{id}" , name="patch_brand" , methods={"PATCH"}) */
         public function   patchBrandById(int $id , Request $request ):Response 
         { 
              try {  
                $this->em= $this->getDoctrine()->getManager(); 
                 $brand = $this->em->getRepository(Brand::class) ->findOneBy(['id' =>$id]) ; 
                 $body= json_decode( $request->getContent() , true) ; 
                 // updating according to the available attribute
                 if(isset($body['name_'])) { $brand->setName($body['name_']); }
                 $this->em->flush(); 
                 $brandJson= $this->hateoas->serialize($brand , 'json'); 
                 return new  Response(  $brandJson , Response::HTTP_OK);

              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);


              }

        }
    
     

         /** @Route("/admin/brand/{id}" , name="delete_brand" , methods={"DELETE"}) */
         public function  deleteBrandById(int $id , Request $request ):Response 
         { 
              try {  
                 $this->em= $this->getDoctrine()->getManager(); 
                 $brand = $this->em->getRepository(Brand::class) ->findOneBy(['id' =>$id]) ; 
                 $this->em->remove($brand);
                 $this->em->flush();
                 return new JsonResponse(['message' => "deleted successfully"], Response::HTTP_OK);
                 
              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

               
              }

        }


         /** @Route("/admin/brands" , name="get_brands" , methods={"GET"}) */
         public function  getbrands(  RouteSettings $rs ):Response 
         { 
              try {  
                 $this->em= $this->getDoctrine()->getManager(); 
                 $brands = $this->em->getRepository(brand::class) ->findAll();  
                 $brandsJson= $this->hateoas
                 ->serialize( $rs->pagination($brands , 'get_brands') , 'json');
                 return new  Response(  $brandsJson , Response::HTTP_OK);
                  
              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

              }

        }
   

   




    }
  