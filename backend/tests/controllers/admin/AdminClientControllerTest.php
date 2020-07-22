<?php
   namespace App\tests\controllers\admin;

        use Symfony\Bundle\FrameworkBundle\KernelBrowser;
        use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

        // testing the routes regarding the Client  entity
        class AdminClientControllerTest extends WebTestCase
        { 
            private $client = null;
            private $admin;
            private $id;
            public function setUp(): void
            {

                $this->client = static::createClient();
                // to call the login method
                $this->admin = self::bootKernel()->getContainer()->get('App\service\Setting');

            }
            public function testClientRoute()
            {
                $this->admin->logIn($this->client, $this);
                $this->get_client();
                $this->get_clients(); 
                $this->delete_client(); 
            }

            public function get_client()
            {
                $this->id = 2;
                $this->client->request('GET', '/admin/client/' . $this->id, [], [], ['content_type' => 'Application/json']);
                 $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
                // assserting that the array is not empty
                $this->assertNotEmpty($this->client->getResponse()->getContent());

            }
            public function get_clients(){ 
                  $this->client->request('GET', '/admin/clients', [], [], ['content_type' => 'Application/json']);
                  $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

            }

            public function  delete_client()
            {
              
                $this->client->request('DELETE', '/admin/client/'. $this->id, [], [], ['content_type' => 'Application/json']);
                 $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
 
            }


        }
