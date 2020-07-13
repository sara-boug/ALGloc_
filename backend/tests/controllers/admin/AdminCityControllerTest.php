<?php 

   namespace tests\controllers\admin;
   use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class AdminCityControllerTest extends WebTestCase { 
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
           $this->getCity(); 
           $this->patchCity(); 
           $this->getCities(); 
           $this->deleteCity(); 
       }

        public function postCity(){ 
            $this->data= [
                "name_"=>"boussada" , 
                "wilaya"=> array( 'id' =>2)
            ];
            $this->client->request(  'POST' , '/admin/city' , [] , []  , ['Content-type'=> 'Application/json'] ,
             json_encode($this->data)); 
             $this->id=(json_decode($this->client->getResponse()->getContent() , true))['id']; 
             $this->assertEquals($this->client->getResponse()-> getStatusCode() , 201  ) ; 

        }

       public function getCity(){ 
           $this->client->request(  'GET' , '/admin/city/1' , [] , []  , ['Content-type'=> 'Application/json']); 

           $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

       }

       public function patchCity(){ 
        $this->data= [
            "name_"=>"Ain Melh" , 
           
        ];
        $this->client->request(  'PATCH' , '/admin/city/1' , [] , []  , ['Content-type'=> 'Application/json'] ,
        json_encode($this->data));   
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }


    public function getCities(){ 
        $this->client->request(  'GET' , '/admin/cities' , [] , []  , ['Content-type'=> 'Application/json']); 
         $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    public function deleteCity(){ 
        $this->client->request(  'DELETE' , '/admin/city/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 
       echo($this->client->getResponse()->getContent()); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }



    }


    


