<?php 
    namespace App\controllers\admin;

use App\Entity\Brand;
use App\Repository\BrandRepository;
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
// /admin/brand/{id} : description : modifying, deleting   a specific brand by id    methods: GET , PATCH, DELETE
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
            return new  Response(  $brandJson , Response::HTTP_CREATED, ["Content-type" => "application\json"]);

        }catch(Exception $e) { 
             return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

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
                 return new  Response(  $brandJson , Response::HTTP_OK, ["Content-type" => "application\json"]);

              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);


              }

        }
    
     

         /** @Route("/admin/brand/{id}" , name="delete_brand" , methods={"DELETE"}) */
         public function  deleteBrandById(int $id ,  BrandRepository $brandRepo ):Response 
         { 
              try {  
                $brandRepo->delete($id); 
                 return new JsonResponse(['message' => "deleted successfully"], Response::HTTP_OK, ["Content-type" => "application\json"]);
                 
              }catch(Exception $e ) { 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

               
              }

        }


   

   




    }
  