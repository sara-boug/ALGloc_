<?php 
   namespace App\tests\controllers\client; 
      use App\Repository\ClientRepository;
      use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
      class  ClientControllerTest  extends WebTestCase   {           
         // token used for the header 
         private $token ; 
            // testing the signup route
         public function testShowPost(){ 
            $client= static::createClient(); 
            $this ->singup($client); 
            $this -> login($client); 
         
         }
            // testing singup function
            public function  singup($client ){     
               $data= array(  
                               'fullname_' => 'bouglam' , 
                               'email' => 'saraboug@gmail.com', 
                               'password' => 'sarasara' , 
                               'address' => 'Algeria ouled fayet', 
                               'phone_number' => '123456789' , 
                               'license_number' => '14782', 
                               'city' => array(
                                  'id'=> 1, 
                                  'name_'=>'algiers'
                               )
            );
               $client ->request('POST' , '/signup' ,[] ,[],['Content_Type' => 'Application/json'] , json_encode($data)) ;
                $this->assertEquals( 200 , $client->getResponse() ->getStatusCode());   
                   
            }      
         // testing the login
         public function login($client) { 
            $data =[
               'email' => 'saraboug@gmail.com', 
               'password' => 'sarasara' , ]  ;   
            $client ->request('POST' , '/login', [],[],['Content_Type' => 'Application/json'] , json_encode($data)) ; 
            $this->token = json_decode(  $client ->getResponse()->getContent(), true)["token"]; 
            $this->assertEquals(200, $client->getResponse()->getStatusCode()); 
             
         } 

         // setting the header for each request
         public function   setup_header() {
               // setting up authorization header for all the routes requring authorization
                return [ 'Authorization' => 'Bearer '.$this->token]; 
         }

      }
?> 