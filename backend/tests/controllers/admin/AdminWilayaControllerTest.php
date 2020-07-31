<?php 

   namespace tests\controllers\admin;
   use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class AdminWilayaControllerTest extends WebTestCase { 
       private $admin ; 
       private $client ; 
       private $data; 
       private $id; 

       public function setUp() :void 
       {   
           $this->client = static::createClient(); 
           $this->admin= self::bootKernel()->getContainer()->get('App\service\Setting'); 

       }

       public function testwilayaRoute(){ 
           $this->admin->logIn($this->client , $this) ; 
           $this->postWilaya(); 
            $this->patchWilaya(); 
           $this->deleteWilaya(); 
        }
         
        public function postWilaya(){ 
            $this->data= ['name_'=> "Adrrar"];
            $this->client->request(  'POST' , '/admin/wilaya' , [] , []  , ['Content-type'=> 'Application/json'] ,
            json_encode($this->data)); 
            $this->client->getResponse()->getContent();
            $this->id=(json_decode($this->client->getResponse()->getContent() , true))['id']; 
            $this->assertEquals($this->client->getResponse()-> getStatusCode() , 201  ); 

        }


       public function patchwilaya(){ 
        $this->data= [
            "name_"=>"M'sila" , 
           
        ];
        $this->client->request(  'PATCH' , '/admin/wilaya/'.$this->id, [] , []  , ['Content-type'=> 'Application/json'] ,
        json_encode($this->data));   
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }



    public function deletewilaya(){ 
        $this->client->request(  'DELETE' , '/admin/wilaya/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 
         $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }



    }


    


