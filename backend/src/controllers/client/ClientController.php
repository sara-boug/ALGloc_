<?php
    namespace App\controllers\client;

    use App\Entity\City;
    use Symfony\Component\Validator\Validator\ValidatorInterface;
    use App\Entity\Client;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; 
    use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface; 
    use App\security\TokenAuthenticator; 
    use Exception;
use Symfony\Component\Security\Core\User\User;

class ClientController extends AbstractController
    {       
        private $auth; 
        private $passwordEncoder; 
            public function __construct( TokenAuthenticator $auth    , UserPasswordEncoderInterface $passwordEncoder)
            {    
                $this->auth = $auth;
                $this ->passwordEncoder = $passwordEncoder; 
                
            }
        
            // this function use to avoid repetition in each route 
                public  function response($body , $statusCode) : Response { 
                    $response = new Response($body , $statusCode);
                    $response->headers->set('content-type', 'Application/json');
                    return $response; 
        
                 }  
        
        /**
         *  @Route("/signup", name="signup_path" , methods= {"post"})
         */
        public function signup(Request $request , ValidatorInterface $validator ): Response
        {   
             try {
                $entityManager = $this->getDoctrine() ->getManager(); 
                $body = json_decode($request->getContent(), true); // request body 
                $client = new Client();      
                $client ->setfullname($body["fullname_"] ); 
                $client ->setemail($body["email"]);
                $client ->setpassword( $body['password']); 
                $client ->setaddress($body["address"]); 
                $client->setphoneNumber($body["phone_number"]);
                $client ->setlicenseNumber($body["license_number"]);
                // finding the city object 
                 $city = $entityManager->getRepository(City::class) ->findOneBy([
                     'id' => $body["city"]["id"] ]);  
                 $client->setcity($city); 
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
                 echo($e->getMessage()); 
                 return $this -> response(   "error" , Response::HTTP_BAD_REQUEST); 
              }        
        }
       
         
        /**
         * @Route("/login" , name="login_path" , methods ={"POST"}) 
        */
       public function login( Request $request ,JWTTokenManagerInterface  $generateToken) :Response { 
            try { 
               // in order to get user repos
              $repos=  $this ->getDoctrine() ->getRepository(Client::class);
              $entityManager = $this->getDoctrine() ->getManager(); 
              // extracting the body content
              $body = json_decode($request ->getContent() , true); 
              if( !empty($body['email'] ) && !empty($body['password'])) {
                    $client =  $repos ->findOneBy(['email' => $body['email'] ]);
                  if($client !=null) {  
                       $verify= $this->passwordEncoder ->isPasswordValid($client ,$body['password']); 
                       if($verify==true) { 
                           // generating  the token  throough the user Interface
                            $user = new User($body['email'] , $client->getPassword());
                            $token  =$generateToken ->create($user); 
                            $client ->setapi_token($token); // attribute in the client entity
                            $entityManager ->persist($client); 
                            $entityManager ->flush();   
                            return $this ->response(json_encode( ['message' => "login success" , 
                                                         'token' =>$client ->getapi_token()]) , Response::HTTP_OK); 
                       } else { 
                        return $this ->response(json_encode( ['error' => 'bad credentials']) 
                        , Response::HTTP_BAD_REQUEST); 
                       }

                  } else { 
                    return $this ->response(json_encode( ['error' => 'bad credentials']) 
                    , Response::HTTP_BAD_REQUEST); 
    
                  }
              } else { 
                return $this ->response(json_encode( ['error' => 'empty values not accepted']) 
                , Response::HTTP_BAD_REQUEST); 

              }
              
            }catch (Exception $e ) { 
                dd($e);
                $exception = array("error" => $e->getMessage()) ; 
                return $this ->response(json_encode($exception) , Response::HTTP_BAD_REQUEST); 

            }
       }
         
    
           
        /**
         * @Route("/logout" , name="logout_path" , methods ={"GET"}) 
        */
        public function logout() :Response{ 

        return $this ->response(json_encode( ['message' => "role User"]) , Response::HTTP_OK); 


       }
 


    }

?>