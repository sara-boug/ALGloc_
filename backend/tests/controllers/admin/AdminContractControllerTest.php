<?php 

   namespace tests\controllers\admin;
   use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
   use App\Entity\Vehicle; 
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

       public function testContractRoute(){ 
           $this->admin->logIn($this->client , $this) ; 
           $this->postContract(); 
           $this->getContracts(); 
           $this->patchContract(); 
           $this->deleteContract(); 

       }

        public function postContract(){ 
            $date = new DateTime(); 
            $this->data= [
                "date"=> 'now', 
                "departure"=> '1-11-2020',
                "arrival"=> '17-12-2020', 
                "client" =>array('id' =>1) , 
                "vehicle" =>array('id' =>1) ,  
 
            ];
             $this->client->request(  'POST' , '/admin/contract' , [] , []  , ['Content-type'=> 'Application/json'] ,
             json_encode($this->data)); 
             $this->assertEquals($this->client->getResponse()-> getStatusCode() , 201  ) ; 
             $this->id=(json_decode($this->client->getResponse()->getContent() , true))['id']; 
             // asserting that data containing departure after arrival are not accepted 
             $data2 = [
                "date"=> 'now', 
                "departure"=> '17-12-2020', 
                "arrival"=> '1-11-2020',
                "client" =>array('id' =>1) , 
                "vehicle" =>array('id' =>1) ,  
 
            ]; 
             $this->client->request(  'POST' , '/admin/contract' , [] , []  , ['Content-type'=> 'Application/json'] ,
            json_encode($data2)); 
            $this->assertEquals($this->client->getResponse()-> getStatusCode() , 400  ) ; 
            // trying to allocate a vehicle already allocated at a specific period 
            $data3 = [
                "date"=> 'now', 
                "departure"=> '10-11-2020',
                "arrival"=> '17-12-2020', 
                "client" =>array('id' =>1) , 
                "vehicle" =>array('id' =>1) ,  
 
            ]; 
            $this->client->request(  'POST' , '/admin/contract' , [] , []  , ['Content-type'=> 'Application/json'] ,
            json_encode($data3)); 
            $this->assertEquals($this->client->getResponse()-> getStatusCode() , 400  ) ; 
 
        }

        public function getContracts(){ 
            $this->client->request(  'GET' , '/admin/contracts' , [] , []  , ['Content-type'=> 'Application/json'] );
            $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 
        }

        public function patchContract(){ 
            $data = ["arrival"=> '18-12-2020']; 
            $this->client->request(  'PATCH' , '/admin/contract/'.$this->id , [] , []  , ['Content-type'=> 'Application/json'] ,
            json_encode($data)); 
            $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 
        }

        public function deleteContract(){ 
            $this->client->request(  'DELETE' , '/admin/contract/'.$this->id , [] , []  , ['Content-type'=> 'Application/json'] ); 
            echo("deleted"); 

            $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 
        }

    }


    


