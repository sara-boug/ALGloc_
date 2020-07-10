<?php

use Gedmo\Mapping\Annotation\Uploadable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile; 
use Gedmo\Sluggable\Util\Urlizer;
class AdminVehicleControllerTest extends WebTestCase{ 
     private $client ; 
     private $admin ; 
     function setUp(){ 
         $this->client= static::createClient(); 
         $this->admin = self::bootKernel()->getContainer()->get('App\service\Setting'); 

     }

     public function testVehicleRoute(){ 
         $this->admin->logIn($this->client, $this); 
         $this->postVehicle(); 
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
          echo($this->client->getResponse()->getContent()); 
     }

    

}

?> 