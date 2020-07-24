<?php
namespace App\tests\controllers\public_access; 
use Liip\FunctionalTestBundle\Test\WebTestCase;

class PublicVehicleControllerTest extends WebTestCase
{
    private $client;
    private $uploader;
    private $id;
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->uploader = self::bootKernel()->getContainer()->get('App\service\FileUploader');
        $this->id=1; 

    }
    public function testPublicVehicleController() { 
            $this->getVehicles();
            $this->getVehicleById(); 
    }

    // testing the vehicles selection
    public function getVehicles()
    {
        $this->client->request('GET', '/public/vehicles', [], [], ['content-Type' => 'Application/json']);
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);
        $this->client->request('GET', '/public/vehicles/agency/' . $this->id, [], [], ['content-Type' => 'Application/json']);
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);
        $this->client->request('GET', '/public/vehicles/model/' . $this->id, [], [], ['content-Type' => 'Application/json']);
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);

    }

    public function getVehicleById()
    {
        $this->client->request('GET', '/public/vehicle/' . $this->id, [], [], ['content-Type' => 'Application/json']);
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);

    }

 

}
