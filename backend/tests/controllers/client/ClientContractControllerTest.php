<?php 
  namespace tests\controllers\client;
  use App\Repository\ClientRepository;
      use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
 
      class  ClientContractControllerTest  extends WebTestCase   {  
         private $client ; 
         private $setting; 
         private $data; 
         // token used for the header 
         private $token ; 
         public function  setUp() :void  { 
            $this->client= static::createClient(); 
            $this->setting = self::bootKernel()->getContainer()->get('App\service\Setting'); 
            //signup the user 
         //    $this->setting->signUpClient($this->client , $this); 
            // this token generated when the user login
            $this->token= $this->setting->loginClient($this->client , $this); 
         
         }
            // testing the signup route
         public function testShowPost(){ 
             $this->postContract(); 
         
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

      }
?> 