<?php 

   namespace tests\controllers\admin;
   use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
 use DateTime; 
    class AdminContractControllerTest extends WebTestCase { 
       private $admin ; 
       private $client ; 
       private $data; 
       private $id; 

       public function setUp() :void 
       {   
           $this->client = static::createClient(); 
           $this->admin= self::bootKernel()->getContainer()->get('App\service\Setting'); 

       }

       public function testCityRoute(){ 
           $this->admin->logIn($this->client , $this) ; 
           $this->postCity(); 

       }

        public function postCity(){ 
            $this->data= [
                "date"=>date('d-m-y'), 
                "arrival"=> date('17-07-2020'), 
                "departure"=> date('17-08-2020'),
                "client" =>array('id' =>1) , 
                "vehicle" =>array('id' =>1) ,  
 
            ];
            $this->client->request(  'POST' , '/admin/contract' , [] , []  , ['Content-type'=> 'Application/json'] ,
             json_encode($this->data)); 
             dd($this->client->getResponse()->getContent()); 
             $this->id=(json_decode($this->client->getResponse()->getContent() , true))['id']; 
             $this->assertEquals($this->client->getResponse()-> getStatusCode() , 201  ) ; 

        }


    }


    


