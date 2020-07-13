<?php
namespace App\controllers\admin;

use App\Entity\Agency;
use App\Entity\City;
use App\Repository\AgencyRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//  routes regarding the agency Controller
// /admin/agency         :  description: add one agency                      methods: post
// /admin/agencies       :  description: iterating through all the agencies  methods: get
// /admin/agency/{id}    :  description: modifying specific agency           methods :Patch  , Delete , get
// /admin/agency/city/id :  description: get agency by city name             methods:  GET

class AdminAgencyController extends AbstractController
{

    private function getRepo($class): ObjectRepository
    {
        return $this->getDoctrine()->getManager()->getRepository($class);
    }
    private function entityManager(): EntityManager
    {
        return $this->getDoctrine()->getManager();
    } 

    private function getJsonAgencyData($agency){ 
         return  ['agency_code' => $agency->getAgencyCode(),
                  'phone_number' => $agency->getPhoneNumber(),
                  'email' => $agency->getemail(),
                  'address' => $agency->getaddress(),
                  'city' => array(
                      'id' => $agency->getcity()->getid(),
                      'name' => $agency->getcity()->getname())];
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
            return new JsonResponse(["success" => $agency], Response::HTTP_CREATED);

        } catch (Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);

        }

    }

    /**
     * @Route("/admin/agencies" , name="get_agencies" , methods={"GET"})
     */
    public function get_agencies(): Response
    {
        try {
            $agencies = $this->getRepo(Agency::class)->findAll();
            $jsonAgencies = [];
            foreach ($agencies as $agency) {
                // turning each data into json jadata
                 $data=$this->getJsonAgencyData($agency); 
                 array_push($jsonAgencies, $data);
            }
            return new JsonResponse(["agencies" => $jsonAgencies], Response::HTTP_OK);

        } catch (Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);

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
              $city = $this->getRepo(City::class)->findOneBy(['id' => $body['city']['id'], 'name_' => $body['city']['name']]);
              $agency->setcity($city);   } 
             $error = $validator->validate($agency);
             if (count($error) > 0) {
                    return new JsonResponse(json_encode(['error' => (string) $error]), 400);
                }
             $this->entityManager()->flush();             
              return new JsonResponse( $this->getJsonAgencyData($agency), Response::HTTP_OK);
          }catch(Exception $e) { 
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);

          }

        }
       
       
        /**
         * @Route("/admin/agency/{id}" , name ="get_agency" , methods={"GET"})
         */
        public function get_agency(int $id):Response {
            try { 
              $agency= $this->getRepo(Agency::class)->find($id);
              return new JsonResponse($this->getJsonAgencyData($agency) , Response::HTTP_OK);
             }catch( Exception $e){ 
              return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);

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
            return new JsonResponse($this->getJsonAgencyData($agency) , Response::HTTP_OK);
           }catch( Exception $e){ 
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
       }

     }
     
         /** 
         * @Route("/admin/agency/city/{id}" , name="get_agency_by_cityId" , methods ={"GET"})
         */
        public function  get_agency_by_cityId(int $id , AgencyRepository $agencyRepo):Response { 
            try { 
              $agencies=$agencyRepo->findByCityId($id); 
              $jsonAgencies=[];
               foreach ($agencies as $agency) {
                  // turning each data into json jadata
                   $data=$this->getJsonAgencyData($agency); 
                   array_push($jsonAgencies, $data);
              }
              return new JsonResponse($jsonAgencies , Response::HTTP_OK);
             }catch( Exception $e){ 
              return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
         }
  
       }
  

    }


