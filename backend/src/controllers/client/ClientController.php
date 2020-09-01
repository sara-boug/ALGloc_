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
    use App\service\RouteSettings;
    use Exception;
    use Symfony\Component\Security\Core\User\User;
    use Symfony\Component\HttpFoundation\JsonResponse; 
    use Hateoas\HateoasBuilder; 
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Hateoas\UrlGenerator\CallableUrlGenerator;
use Symfony\Component\Routing\RouterInterface;

class ClientController extends AbstractController
    {       
        private $hateoas; 
        private $passwordEncoder; 
            public function __construct(  
                UserPasswordEncoderInterface $passwordEncoder , RouterInterface $router)
            {    
                 $this ->passwordEncoder = $passwordEncoder; 

                $this->hateoas= HateoasBuilder::create()
                ->setUrlGenerator(
                    null , 
                    new CallableUrlGenerator(function($route , array $parameter , $absolute) use ( $router ){ 
                             return $router->generate($route  , $parameter , UrlGeneratorInterface::ABSOLUTE_URL);
                    })
                )->build(); 
    
    
                
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
                    return new JsonResponse(["error" => $errors], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                } 
                // if validated then the pssword would be encoded 
                $client ->setpassword( $this->passwordEncoder->encodePassword($client , $body['password'])); 
                 unset($body['password']);
                // inserting the data into the database 
                 $entityManager ->persist($client); 

                //executing the query 
                $entityManager ->flush(); 
                return new  JsonResponse( ["message" =>"signup success"], Response::HTTP_OK , ["Content-type" => "application\json"]);
                
              } catch( Exception $e) {       
                return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

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
                            $user = new User($body['email'] , $client->getPassword() , $client->getRoles());
                            $token  =$generateToken ->create($user); 
                            $client ->setapi_token($token); // attribute in the client entity
                            $entityManager ->persist($client); 
                            $entityManager ->flush();   
                            $client->setLink("get_client_profile"); 

                            $clientJson= $this->hateoas->serialize($client , 'json');
                            return new Response( $clientJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                       } else { 
                        return new JsonResponse(["error" => "Bad Credentials"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                    }

                  } else { 
                    return new JsonResponse(["error" => "Bad Credentials"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
    
                  }
              } else { 
                return new JsonResponse(["error" => "Bad Credentials"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

              }
              
            }catch (Exception $e ) { 
                return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
            }
       }
         
    
           
        /**
         * @Route("/logout" , name="logout_path" , methods ={"GET"}) 
        */
        public function logout() :Response{ 

            return new JsonResponse(["error" => "logout Success"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);


       } 


       /** @Route("/client/current" , name="get_current_client" , methods={"GET"}) */
       public function getClientCurrent( RouteSettings $routeSettings ){ 
         try { 
             $em = $this->getDoctrine()->getManager(); 
             if(! $this->getUser()) { 
              return new JsonResponse( ["message" => "not Found"],  Response::HTTP_NOT_FOUND, ["Content-type" => "application\json"]);

             }
             $client= $routeSettings->getCurrentClient($em , $this->getUser());
             // ensuring that the User can not access other user's profile as well as data
            $client->setLink("get_client_profile"); 
            $clientJson = $this->hateoas->serialize($client , 'json'); 
            return new Response($clientJson, Response::HTTP_OK, ["Content-type" => "application\json"]);
 
         }catch(Exception $e )  {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

         }

       }
 

              /** @Route("/client/{id}/profile" , name="get_client_profile" , methods={"GET"}) */
              public function getClient(int $id , RouteSettings $routeSettings ){ 
                try { 
                    $em = $this->getDoctrine()->getManager(); 
                    $client= $routeSettings->getCurrentClient($em , $this->getUser());
                    // enssuring that the User can not access other user's profile as well as data
                     if($id != $client->getid()) { 
                        return new JsonResponse(["error" => "Not Found"], Response::HTTP_NOT_FOUND, ["Content-type" => "application\json"]);
                    }
                   $client->setLink("get_client_profile"); 
                  $clientJson = $this->hateoas->serialize($client , 'json'); 
                  return new Response($clientJson, Response::HTTP_OK, ["Content-type" => "application\json"]);
        
                }catch(Exception $e )  {
                   return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
       
                }
       
              }
        


    }

?>