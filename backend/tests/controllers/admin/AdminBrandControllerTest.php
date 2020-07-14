<?php 

   namespace tests\controllers\admin;
   use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class AdminBrandControllerTest extends WebTestCase { 
       private $admin ; 
       private $client ; 
       private $data; 
       private $id; 

       public function setUp() :void 
       {   
           $this->client = static::createClient(); 
           $this->admin= self::bootKernel()->getContainer()->get('App\service\Setting'); 

       }

       public function testBrandRoute(){ 
           $this->admin->logIn($this->client , $this) ; 
           $this->postBrand(); 
           $this->getBrand(); 
           $this->patchBrand(); 
           $this->getBrands(); 
           $this->deleteBrand(); 
       }

        public function postBrand(){ 
            $this->data= [
                "name_"=>"skoda" , 
            ];
            $this->client->request(  'POST' , '/admin/brand' , [] , []  , ['Content-type'=> 'Application/json'] ,
             json_encode($this->data)); 
             $this->id=(json_decode($this->client->getResponse()->getContent() , true))['id']; 
             $this->assertEquals($this->client->getResponse()-> getStatusCode() , 201  ) ; 

        }

       public function getBrand(){ 
           $this->client->request(  'GET' , '/admin/brand/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 

           $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

       }

       public function patchBrand(){ 
        $this->data= [
            "name_"=>"Jaguar" , 
           
        ];
        $this->client->request(  'PATCH' , '/admin/brand/'.$this->id, [] , []  , ['Content-type'=> 'Application/json'] ,
        json_encode($this->data));   
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }


    public function getBrands(){ 
        $this->client->request(  'GET' , '/admin/brands' , [] , []  , ['Content-type'=> 'Application/json']); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    public function deleteBrand(){ 
        $this->client->request(  'DELETE' , '/admin/brand/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 
         $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }



    }


    


