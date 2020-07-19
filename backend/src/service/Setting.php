<?php
    namespace  App\service;

use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
    // a class containing all the repetetive  and main methods that would used in test case
     class Setting
    {
        public function logIn(KernelBrowser $client, WebTestCase $test)
        {
            
         try {
            $client->request('POST', '/admin/login', [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                '{
                    "email": "admin",
                    "password": "admin"
                }');

             $test->assertEquals(200, $client->getResponse()->getStatusCode());
         }catch( Exception $e) { 
          }
        }

        public function logout(KernelBrowser $client, WebTestCase $test)
        {
            $client->request('Get', '/admin/logout');
            $test->assertEquals(302, $client->getResponse()->getStatusCode());

        }
    }
