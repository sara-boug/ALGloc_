<?php
namespace App\controllers\admin;

use App\Entity\Agency;
use App\Entity\City;
use App\Repository\AgencyRepository;
use App\service\FileUploader;
use App\service\RouteSettings;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Hateoas\HateoasBuilder; 
use Exception;
use Hateoas\Hateoas;
use Hateoas\UrlGenerator\CallableUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//  routes regarding the agency Controller
// /admin/agency         :  description: add one agency                      methods: post
// /admin/agencies       :  description: iterating through all the agencies  methods: get
// /admin/agency/{id}    :  description: modifying specific agency           methods :Patch  , Delete , get
// /admin/agency/city/id :  description: get agency by city name             methods:  GET

class AdminAgencyController extends AbstractController
{
     
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
    private function getRepo($class): ObjectRepository
    {
        return $this->getDoctrine()->getManager()->getRepository($class);
    }
    private function entityManager(): EntityManager
    {
        return $this->getDoctrine()->getManager();
    } 

    /**
     * @Route("/admin/agency"  , name="agency_add" , methods ={"POST"})
     */
    public function post_agency(Request $request, ValidatorInterface $validator): Response
    {
        try {
            $body = json_decode($request->getContent(), true);
            $agency = new Agency( );
            $agency->setAgencyCode($body['agency_code']);
            $agency->setPhoneNumber($body['phone_number']);
            $agency->setemail($body['email']);
            $agency->setaddress($body['address']);
            $city = $this->getRepo(City::class)->findOneBy(['id' => $body['city']['id']]);
            $agency->setcity($city);
            // validating the agency object
            $error = $validator->validate($agency);
            if (count($error) > 0) {
                return new JsonResponse(json_encode(['error' => (string) $error]), 400);
            }
            $this->entityManager()->persist($agency);
            $this->entityManager()->flush();
            $jsonAgency = $this->hateoas->serialize($agency , 'json'); 
            return new Response( $jsonAgency, Response::HTTP_CREATED , ["Content-type" => "application\json"]);

        } catch (Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

        }

    }

    /**
     * @Route("/admin/agencies" , name="get_agencies" , methods={"GET"})
     */
    public function get_agencies(RouteSettings $setting): Response
    {
        try {
            $agencies = $this->getRepo(Agency::class)->findAll();
             $jsonAgencies =$this->hateoas->serialize( $setting->pagination($agencies ,"get_agencies" ), 'json'  ) ;        
            return new Response($jsonAgencies, Response::HTTP_OK, ["Content-type" => "application\json"]);

        } catch (Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

        }
    } 
     /**
      * @Route("/admin/agency/{id}" , name="agency_patch" , methods={"PATCH"}) 
      */
     public function patch_agency( Request $request , ValidatorInterface $validator , int $id ):Response{ 
          try { 
            $body = json_decode($request->getContent(), true);
           // $id =$request->attributes->get('id'); 
            $agency= $this->getRepo(Agency::class)->find($id);
             // updating the vaules according to their availability from the body 
             if( isset( $body["agency_code"] ) )  $agency->setagency_code($body['agency_code']);  
             if(isset($body['phone_number'] ) ) $agency->setphone_number($body['phone_number']);  
             if(isset($body['email']  ))        $agency->setemail($body['email']);
             if(isset($body['address'] ) )      $agency->setaddress($body['address']);
             if(isset($body['city'])) {
              $city = $this->getRepo(City::class)->findOneBy(['id' => $body['city']['id']]);
              $agency->setcity($city);   } 
             $error = $validator->validate($agency);
             if (count($error) > 0) {
                    return new JsonResponse(json_encode(['error' => (string) $error]), 400);
                }
             $this->entityManager()->flush();             
             $jsonAgency = $this->hateoas->serialize($agency , 'json'); 
             return new Response( $jsonAgency, Response::HTTP_OK , ["Content-type" => "application\json"]);
           }catch(Exception $e) { 
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

          }

        }
       
       
        /**
         * @Route("/admin/agency/{id}" , name ="get_agency" , methods={"GET"})
         */
        public function get_agency(int $id):Response {
            try { 
              $agency= $this->getRepo(Agency::class)->find($id);
              $jsonAgency = $this->hateoas->serialize($agency , 'json'); 
              return new Response( $jsonAgency,Response::HTTP_OK, ["Content-type" => "application\json"]);
               }catch( Exception $e){ 
              return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

             }
        }
        /** 
         * @Route("/admin/agency/{id}" , name="delete_agency" , methods ={"DELETE"})
         */
        public function delete_agency(int $id):Response { 
          try { 
            $agency= $this->getRepo(Agency::class)->find($id);
              $this->entityManager()->remove($agency); 
             $this->entityManager()->flush();
             return new JsonResponse( ["message" =>'deleted'], Response::HTTP_OK , ["Content-type" => "application\json"]);
            }catch( Exception $e){ 
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
       }

     }
     
         /** 
         * @Route("/admin/agency/city/{id}" , name="get_agency_by_cityId" , methods ={"GET"})
         */
        public function  get_agency_by_cityId(int $id , AgencyRepository $agencyRepo ,RouteSettings $setting):Response { 
            try { 
              $agencies=$agencyRepo->findByCityId($id); 
              $jsonAgencies =$this->hateoas->serialize( $setting->pagination($agencies ,"get_agencies" ), 'json'  ) ;        
              return new Response($jsonAgencies, Response::HTTP_OK, ["Content-type" => "application\json"]);
  
             }catch( Exception $e){ 
              return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
         }
  
       }
  

    }


