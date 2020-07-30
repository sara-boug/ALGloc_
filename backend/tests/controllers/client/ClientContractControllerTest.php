<?php 
  namespace tests\controllers\client;
  use App\Repository\ClientRepository;
      use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
 
      class  ClientContractControllerTest  extends WebTestCase   {  
         private $client ; 
         private $setting; 
         private $data; 
         private $id;
         private $currentClient;
         // token used for the header 
         private $token ; 
         public function  setUp() :void  { 
            $this->client= static::createClient(); 
            $this->setting = self::bootKernel()->getContainer()->get('App\service\Setting'); 
            //signup the user 
             //$this->setting->signUpClient($this->client , $this); 
            // this token generated when the user login
            $login =  $this->setting->loginClient($this->client , $this);
            $this->token= $login['token']; 
            $this->id=$login['id'];
         
         }
            // testing the signup route
         public function testShowPost(){ 
            $this->getClientProfile(); 
            // $this->postContract(); 
           //   $this->getContracts(); 
         
         }
         public function getClientProfile() { 
            // testing whether a client can access another user's profile
            $testId= $this->id+1;
            $this->client ->request('GET' , '/client/'. $testId . '/profile' 
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ]);
           $this->assertEquals($this->client->getResponse()->getStatusCode() , 401); 
          // asserting that a client can access his own profile 
           $this->client ->request('GET' , '/client/'. $this->id. '/profile' 
           ,  [] ,[],['content_type' => 'Application/json' , 
                  'Authorization' => 'Bearer '.$this->token
                 ]);
          $this->assertEquals($this->client->getResponse()->getStatusCode() ,200); 

         }
         public function postContract(){ 
             $this->data = [
               "date"=> 'now', 
               "departure"=> '1-11-2020',
               "arrival"=> '17-12-2020', 
               "vehicle" =>array('id' =>1) 
            ] ;   // array('Authorization' => 'Bearer '.$this->token)
            // setting up the header for the authorizition
             $this->client ->request('POST' , '/client/contract' 
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ],json_encode($this->data)
     

            ) ; 
          $this->assertEquals($this->client->getResponse()->getStatusCode() , 200); 
           
         }
           
         public function getContracts(){ 
       
            $this->client ->request('GET' , '/admin/contracts' 
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ] );  
             // 401 since a client can not a access an admin ressource 
            $this->assertEquals($this->client->getResponse()->getStatusCode() , 401); 
   
         }

      }
?> 