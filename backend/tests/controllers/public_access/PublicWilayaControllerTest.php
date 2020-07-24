<?php
    namespace App\controllers\public_access;

        use Liip\FunctionalTestBundle\Test\WebTestCase;

        class PublicWilayaControllerTest extends WebTestCase
        {

            private $client;
            private $id;

            public function setUp(): void
            {
                $this->client = static::createClient();
                $this->id = 1;

            }
            public function testPublicWilayaRoutes()
            {
                $this->getwilaya();
                $this->getWilayas();
                $this->getCities();
                $this->getcity();
            }

            public function getwilaya()
            {
                $this->client->request('GET', '/public/wilaya/'. $this->id, [], [], ['Content-type' => 'Application/json']);
                $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);

            }

            public function getWilayas()
            {
                $this->client->request('GET', '/public/wilayas', [], [], ['Content-type' => 'Application/json']);
                $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);

            }
            public function getCity()
            {
                $this->client->request('GET', '/public/city/' . $this->id, [], [], ['Content-type' => 'Application/json']);

                $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);

            }

            public function getCities()
            {
                $this->client->request('GET', '/public/cities', [], [], ['Content-type' => 'Application/json']);
                $this->assertEquals($this->client->getResponse()->getStatusCode(), 200);

            }

        }
