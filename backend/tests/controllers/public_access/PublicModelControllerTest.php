<?php
namespace App\controllers\public_access; 
use Liip\FunctionalTestBundle\Test\WebTestCase;

class PublicModelControllerTest extends WebTestCase { 
    
    private $client = null;
    private $id; 
    public function setUp():void 
    {    // no need to sign up since it's a public access 
       $this->client = static::createClient();
         // to call the login method
        $this->id= 3 ; 
    }   

   public function testPublicModelRoute(){ 
       $this->getModel(); 
       $this->getModels(); 
       $this->getModelsByBrand(); 
       $this->getModelsByCategory(); 
       $this->getCategories(); 
       $this->getCategory(); 
       $this->getBrand(); 
       $this->getBrands(); 
   }
    public function getModelsByBrand(){ 
        $brandId= 1; 
        $this->client->request(  'GET' , '/public/models/brand/'.$brandId , [] , []  , ['Content-type'=> 'Application/json']); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 
        $models = json_decode($this->client->getResponse()-> getContent() ,true); 
        $this->assertEquals( $models[0]["brand"]["id"] , $brandId ) ; 
   }

     public function getModelsByCategory(){ 
       $categoryId= 4; 
       $this->client->request(  'GET' , '/public/models/category/'.$categoryId , [] , []  , ['Content-type'=> 'Application/json']); 
       $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 
     }


    public function getModel(){ 
        $this->client->request(  'GET' , '/public/model/1' , [] , []  , ['Content-type'=> 'Application/json']); 

        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    public function getModels(){ 
        $this->client->request(  'GET' , '/public/models' , [] , []  , ['Content-type'=> 'Application/json']); 
 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }


    public function getBrand(){ 
        $this->client->request(  'GET' , '/public/brand/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 

        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    public function getBrands(){ 
        $this->client->request(  'GET' , '/public/brands' , [] , []  , ['Content-type'=> 'Application/json']); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    public function getCategory(){ 
        $this->client->request(  'GET' , '/public/category/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 

        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    public function getCategories(){ 
        $this->client->request(  'GET' , '/public/categories' , [] , []  , ['Content-type'=> 'Application/json']); 
        $this->client->getResponse()->getContent(); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }




}


?> 