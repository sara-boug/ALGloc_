<?php
namespace App\tests\controllers\admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    private $client = null;
    public function setUp()
    {
        $this->client = static::createClient();

    }
    public function testShowPost()
    {
        $this->logIn();
        $this->client->request('GET', '/admin/agency', [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }
    private function logIn()
    {

        $this->client->request('POST', '/admin/login', [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                  "email": "admin",
                  "password": "admin"
              }');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
