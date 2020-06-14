<?php 
   namespace App\tests\controllers; 
      use App\Repository\ClientRepository;
      use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
      

      class  ClientControllerTest  extends WebTestCase   { 
            // testing the signup route
         public function testShowPost(){ 
            $this ->singup(); 
         
         }
            // testing singup function
            public function  singup(){ 
               $client= static::createClient(); 
               $clientRepos= static::$container->get(ClientRepository::class);
               $clientRepos->deleteAll(); 
   
               $data= array(  'name_'  => 'sara' , 
                              'familyname' => 'bouglam' , 
                              'email' => 'saraboug@gmail.com', 
                              'password' => 'sarasara' , 
                              'address' => 'Algeria ouled fayet', 
                              'phone_number' =>  '123456789' , 
                              'license_number' =>   '14782'
            );
               $client ->request('POST' , '/signup' ,[] ,[],['Content_Type' => 'Application/json'] , json_encode($data)) ; 
               $this->assertEquals( 200 , $client->getResponse() ->getStatusCode());   
               $this->assertEquals( json_encode($data) , $client ->getResponse()->getContent());    
                  
            }

      }


?> 