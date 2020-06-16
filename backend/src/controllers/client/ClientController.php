<?php
    namespace App\controllers\client;
    use Symfony\Component\Validator\Validator\ValidatorInterface;
    use App\Entity\Client; 
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; 
    use Exception;

class ClientController extends AbstractController
    {       
        private $passwordEncoder; 
            public function __construct(UserPasswordEncoderInterface $passwordEncoder)
            {
                $this ->passwordEncoder = $passwordEncoder; 
                
            }
        
            // this function use to avoid repetition in each route 
                public  function response($body , $statusCode) : Response { 
                    $response = new Response($body , $statusCode);
                    $response->headers->set('content-type', 'Application/json');
                    return $response; 
        
                 }  
        
        /**
        *@Route("/signup", name="signup" , methods= {"post"})
        */
        public function signup(Request $request , ValidatorInterface $validator ): Response
        {   
             try {
                $entityManager = $this->getDoctrine() ->getManager(); 
                $body = json_decode($request->getContent(), true); // request body 
                $client = new Client(); 
      
                $client ->setname_($body["name_"]); 
                $client->setfamilyname($body["familyname"]);
                $client ->setemail($body["email"]);
                $client ->setpassword( $body['password']); 
                $client ->setaddress($body["address"]); 
                $client->setphone_number($body["phone_number"]);
                $client ->setlicense_number($body["license_number"]);
                $errors=$validator ->validate($client);  // validating the user input
                if(count($errors)>0) {                 
                    $errorsJson= json_encode(  array( "error" =>(string)$errors));
                    return $this->response($errorsJson , Response::HTTP_BAD_REQUEST);   
                } 
                // if validated then the pssword would be encoded 
                $client ->setpassword( $this->passwordEncoder->encodePassword($client , $body['password'])); 
                 unset($body['password']);
                // inserting the data into the database 
                $entityManager ->persist($client); 
                //executing the query 
                $entityManager ->flush(); 
                return $this ->response(json_encode($body), Response::HTTP_OK);
             } catch( Exception $e) {                 
                 print($e); 
                 return $this -> response(   "error" , Response::HTTP_BAD_REQUEST); 
              }        
        }
       

        





    }

?>