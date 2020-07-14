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
           $this->getWilaya(); 
           $this->patchWilaya(); 
           $this->getWilayas(); 
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

       public function getwilaya(){ 
           $this->client->request(  'GET' , '/admin/wilaya/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 
           $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 
           $wilayaName= (json_decode($this->client->getResponse()->getContent() , true))['name_']; 
          // asserting that the data in the body is the same as the one sent to the db
           $this->assertEquals($wilayaName ,strtolower( $this->data['name_'])); 

       }

       public function patchwilaya(){ 
        $this->data= [
            "name_"=>"M'sila" , 
           
        ];
        $this->client->request(  'PATCH' , '/admin/wilaya/'.$this->id, [] , []  , ['Content-type'=> 'Application/json'] ,
        json_encode($this->data));   
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }


    public function getWilayas(){ 
        $this->client->request(  'GET' , '/admin/wilayas' , [] , []  , ['Content-type'=> 'Application/json']); 
         $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    public function deletewilaya(){ 
        $this->client->request(  'DELETE' , '/admin/wilaya/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 
       echo($this->client->getResponse()->getContent()); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }



    }


    


