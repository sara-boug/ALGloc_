<?php 

   namespace tests\controllers\admin;
   use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class AdminCategoryControllerTest extends WebTestCase { 
       private $admin ; 
       private $client ; 
       private $data; 
       private $id; 

       public function setUp() :void 
       {   
           $this->client = static::createClient(); 
           $this->admin= self::bootKernel()->getContainer()->get('App\service\Setting'); 

       }

       public function testCategoryRoute(){ 
           $this->admin->logIn($this->client , $this) ; 
           $this->postCategory(); 
           $this->getCategory(); 
           $this->patchCategory(); 
           $this->getCategories(); 
           $this->deleteCategory(); 
       }

        public function postCategory(){ 
            $this->data= [
                "name_"=>"Large" 
            ];
            $this->client->request(  'POST' , '/admin/category' , [] , []  , ['Content-type'=> 'Application/json'] ,
             json_encode($this->data)); 
             $this->id=(json_decode($this->client->getResponse()->getContent() , true))['id']; 
             $this->assertEquals($this->client->getResponse()-> getStatusCode() , 201  ) ; 

        }

       public function getCategory(){ 
           $this->client->request(  'GET' , '/admin/category/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 

           $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

       }

       public function patchCategory(){ 
        $this->data= [
            "name_"=>"extra Large" , 
           
        ];
        $this->client->request(  'PATCH' , '/admin/category/'.$this->id, [] , []  , ['Content-type'=> 'Application/json'] ,
        json_encode($this->data));   
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }


    public function getCategories(){ 
        $this->client->request(  'GET' , '/admin/categories' , [] , []  , ['Content-type'=> 'Application/json']); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    public function deleteCategory(){ 
        $this->client->request(  'DELETE' , '/admin/category/'.$this->id , [] , []  , ['Content-type'=> 'Application/json']); 
         $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  ) ; 

    }

    }


    


