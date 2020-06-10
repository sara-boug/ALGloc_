<?php 
    namespace   App\controllers;

use Client;
use Symfony\Component\HttpFoundation\Response; 
    use Symfony\Component\Routing\Annotation\ Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;

class ClientController   extends AbstractController{ 

          /**
           @Route("/signup", name="signup" , methods= {"post"})
          */ 
        public function signup(Request $request){ 
            $data = array('message' => 'i am json resposne');  
            $body = json_decode( $request ->getContent()  ,true) ;  // request body 
            $client = new Client($body["name"] , $body["familyName"] , 
            $body["email"] ,  $body['password'],  $body["address"]  ,
             $body["phoneNumber"] , $body["licenseNumber"]) ; 
            
            // response 
            $response = new Response( json_encode( $body), Response::HTTP_OK ) ; 
            $response ->headers->set('content-type', 'Application/json')  ;              
            return $response;   
             

          }
           
 

    }

?>