<?php 
  namespace App\controllers\admin ; 
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
  use Symfony\Component\Routing\Annotation\Route; 
  use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;
  use App\Entity\Agency;
  use App\Entity\City;
 use Doctrine\Common\Persistence\ObjectRepository;
 use Doctrine\ORM\EntityManager;
 use  Exception;
  use Symfony\Component\HttpFoundation\JsonResponse;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Validator\Validator\ValidatorInterface; 

//  routes regarding the agency Controller
   // /admin/agency     :  description: add one agency                       methods: post
   // /admin/agencies   :  description: iterating through all the agencies   methods: get 
   // /admin/agency/id  :  description: modifying specific agency            methods :   Patch , Update , Delete , get
   //  
    
  class AdminAgencyController extends AbstractController { 
     
      private function getRepo($class) : ObjectRepository{ 
        return  $this->getDoctrine()->getManager()->getRepository($class) ;
      }
      private function entityManager():EntityManager{ 
        return  $this->getDoctrine()->getManager() ;
      }
     /** 
      * @Route("/admin/agency"  , name="agency_add" , methods ={"POST"})
      */
     public  function agency_post(Request $request ,ValidatorInterface $validator ) :Response{ 
         try { 
             $body =  json_decode($request->getContent() , true); 
             $agency = new Agency(); 
             $agency->setagency_code($body['agency_code']); 
             $agency->setphone_number($body['phone_number']); 
             $agency->setemail($body['email']); 
             $agency->setaddress($body['address']); 
             $city =$this->getRepo(City::class) ->findOneBy(['id' =>$body['city']['id'] , 'name_' =>$body['city']['name']]); 
             $agency ->setcity($city);    
             // validating the agency object           
             $error=$validator->validate($agency); 
             if(count($error)>0) {  
                 return new JsonResponse( json_encode(['error'=> (string) $error]) , 400);
                }
             $this->entityManager()->persist($agency); 
             $this->entityManager()->flush();
             return new JsonResponse(["success" =>$agency], Response::HTTP_CREATED); 
           
         }catch(Exception $e) { 
             return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST); 

         }

     }

    
  }
  

 