<?php 

   namespace tests\controllers\admin;
   use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class AdminModelControllerTest extends WebTestCase { 
       private $admin ; 
       private $client ; 
       private $data; 
       private $id; 

       public function setUp() :void 
       {   
           $this->client = static::createClient(); 
           $this->admin= self::bootKernel()->getContainer()->get('App\service\Setting'); 

       }

       public function testModelRoute(){ 
           $this->admin->logIn($this->client , $this) ; 
           $this->postModel(); 
           $this->getModel(); 
           $this->patchModel(); 
           $this->getModels(); 
           $this->deleteModel(); 
       }

        public function postModel(){ 
            $this->data= [
                 "name_"=> "Alfa Romeo 88" , 
                 "brand" =>array('id' => 4), 
                 "category" =>array('id' => 5)
            ];
            $this->client->request(  'POST' , '/admin/model' , [] , []  , ['Content-type'=> 'Application/json'] ,
             json_encode($this->data)); 
            $this->id=(json_decode($this->client->getResponse()->getContent() , true))['id']; 
             $this->assertEquals($this->client->getResponse()-> getStatusCode() , 201  ) ; 

        }

       public function getModel(){ 
           $this->client->request(  'GET' , '/admin/model/1' , [] , []  , ['Content-type'=> 'Application/json']); 

           $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

       }

       public function patchModel(){ 
        $this->data= [
            "name_"=>"M'sila" , 
           
        ];
        $this->client->request(  'PATCH' , '/admin/model/'.$this->id, [] , []  , ['Content-type'=> 'Application/json'] ,
        json_encode($this->data));   
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }


    public function getModels(){ 
        $this->client->request(  'GET' , '/admin/models' , [] , []  , ['Content-type'=> 'Application/json']); 
 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    public function deleteModel(){ 
        $this->client->request(  'DELETE' , '/admin/model/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }



    }


    

