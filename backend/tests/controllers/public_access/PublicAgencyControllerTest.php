<?php
  namespace App\tests\controllers\public_access; 
  use Liip\FunctionalTestBundle\Test\WebTestCase;

class  PublicAgencyControllerTest  extends WebTestCase{ 

    private $client = null  ;
    private $id; 
    public function setUp():void 
    {    // no need to sign up since it's a public access 
       $this->client = static::createClient();
         // to call the login method
        $this->id= 3 ; 
    }   
    public function  testAgencyRoute(){ 
        $this->getAgencies(); 
        $this->getAgency(); 
        $this->getAgency_by_cityId(); 

    }


    public function getAgencies(){ 
        $this->client->request('GET', '/public/agencies' , [] ,[],['content_type' => 'Application/json']); 
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); 
        // assserting that the array is not empty 
        $this->assertNotEmpty($this->client->getResponse()->getContent() ); 
         
    } 

    public function getAgency(){ 
        $this->client->request('GET', '/public/agency/'.$this->id , [] ,[],['content_type' => 'Application/json']); 
         $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); 
        // assserting that the data was really updated 
 
    }

    
    public function getAgency_by_cityId(){ 
        $this->client->request('GET', '/public/agency/city/1', [] ,[],['content_type' => 'Application/json']); 
        $result =  $this->client->getResponse()->getContent();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); 
        // assserting that the data was really updated 
        // asserting that we are getting the real data
        $this->assertEquals(count($result) , 1 ); 

    }

    




}

  


?> 













?> 