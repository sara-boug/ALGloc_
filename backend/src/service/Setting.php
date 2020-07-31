<?php
    namespace  App\service;
    use Exception;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\User;

// a class containing all the repetetive  and main methods that would used in test case
     class Setting
     {  
         private  $auth; 
        public function  __construct(JWTTokenManagerInterface $auth)
        {
             $this->auth=  $auth; 
        }
        // used to login the  admin 
        public function logIn(KernelBrowser $client, WebTestCase $test)
        {
            
         try {
            $client->request('POST', '/admin/login', [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                '{
                    "email": "admin",
                    "password": "admin"
                }');
 
             $test->assertEquals(200, $client->getResponse()->getStatusCode());
         }catch( Exception $e) { 
             
           }
        }

        public function logout(KernelBrowser $client, WebTestCase $test)
        {
            $client->request('Get', '/admin/logout');
            $test->assertEquals(302, $client->getResponse()->getStatusCode());

        }
        // signup Client
        public function signUpClient(  KernelBrowser $client , WebTestCase $test ){ 
                        $data= array(  
                            'fullname_' => 'client' , 
                            'email' => 'client@gmail.com', 
                            'password' => 'passwordClient' , 
                            'address' => 'address', 
                            'phone_number' => '123456789' , 
                            'license_number' => '14782', 
                            'city' => array(
                            'id'=> 1, 
                            'name_'=>'algiers'
                            )
            );
            $client ->request('POST' , '/signup' ,[] ,[],['Content_Type' => 'Application/json'] , json_encode($data)) ;
             $test->assertEquals( 200 , $client->getResponse() ->getStatusCode());   
                
            }  
        // login the client which matches the signUpClient function 
        public function loginClient(KernelBrowser $client , WebTestCase $test){ 
            $data =[
                'email' => 'client@gmail.com', 
                'password' => 'passwordClient' ] ; 
              $client ->request('POST' , '/login', [],[],['Content_Type' => 'Application/json'] , json_encode($data)) ; 

              $data =  json_decode($client->getResponse()->getContent(), true)  ; 
               $test->assertEquals(200, $client->getResponse()->getStatusCode()); 
                // token and the id are necessary for testing later on 
              return  [
                   'token' =>  $this->generateToken()  , 
                   'id' =>$data['id']
              ]; 

        }

                 // setting the header for each request
                 public function    header($token) {
                    // setting up authorization header for all the routes requring authorization
                     return [ 'Authorization' => 'Bearer '.$token]; 
              }

              public function generateToken() { 
                  $user = new User('client@gmail.com' , 'passwordClient', ['ROLE_USER']); 
                  $token = $this->auth->create($user);
                  return $token; 
              }
     

  }

