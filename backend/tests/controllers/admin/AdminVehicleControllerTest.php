<?php

use App\Entity\Vehicle;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile; 
 class AdminVehicleControllerTest extends WebTestCase{ 
     private $client ; 
     private $admin ; 
     private $uploader; 
     private $id ; 
     function setUp():void  { 
         $this->client= static::createClient();
         $this->admin = self::bootKernel()->getContainer()->get('App\service\Setting');
         $this->uploader= self::bootKernel()->getContainer()->get('App\service\FileUploader');

 
     }

     public function testVehicleRoute(){ 
         $this->admin->logIn($this->client, $this); 
         $this->postVehicle(); 
         $this->patchVehicleById(); 
         $this->getVehicleImage(); 

         $this->deleteVehicleById();
     }
     
     public function postVehicle() { 
          $image= new UploadedFile( 'tests\imageFolderTest\car.jpeg' , 'car.jpeg'  , 'image.jpeg' , null); 
          $data = [
            "registration_number" => "200616D24"  , 
            "rental_price" =>6000, 
            "deposit"=>2000 , 
            "inssurance_price"=>1000,
            "passenger_number"=> 5,
            "image_" =>$image, 
            "suitcase_number" =>5,
            "gearbox" =>"automatique", 
            "state" => "new", 
            "status"=>"allouer",
            "agency" => array( "id" =>1 ) , 
            "model" => array("id" =>1  )
                 ]; 
 
          $this->client->request( 'POST', '/admin/vehicle' , [],['car' =>$image] , ['content-Type' => 'Application/json'] , 
          json_encode($data) ); 
          $this->assertEquals($this->client->getResponse()-> getStatusCode() , 201); 
          $this->id=(json_decode($this->client->getResponse()->getContent() , true))['id']; 

      }
       
         // in order to test the the image uploaded by the admin 
    public function getVehicleImage() { 
        $this->client->request( 'GET', '/public/vehicle/'.$this->id.'/image' ); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode(), 200)  ;
         $this->assertEquals( $this->client->getResponse()->headers->get("content-type") , "image/png"); 
        // clearing the 
        $this->uploader->deleteFolder(); 

        
  
    }



    public function patchVehicleById() { 
        $image= new UploadedFile( 'tests\imageFolderTest\car.jpeg' , 'car.jpeg'  , 'image.jpeg' , null); 
        $data = [
          "registration_number" => "200616C33"  , 
        ]; 
      
        $this->client->request( 'PATCH', '/admin/vehicle/'.$this->id , [],['car' =>$image] , ['content-Type' => 'Application/json'] , 
        json_encode($data) ); 

        $vehicleTable = static::$container->get('doctrine')->getManager()->getRepository( Vehicle::class) ;
        $vehicle=  $vehicleTable->findOneBy(['id' =>$this->id]); 
        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200);
        // asserting that the value is really updated  
        $this->assertEquals($vehicle->getRegistrationNumber() , $data["registration_number"]); 

    }



    public function deleteVehicleById() { 
        $this->client->request( 'DELETE', '/admin/vehicle/'.$this->id , [],[] , ['content-Type' => 'Application/json']  ); 

        $this->assertEquals($this->client->getResponse()-> getStatusCode() , 200  )  ; 

    }


    

}

?> 