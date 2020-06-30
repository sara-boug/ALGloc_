<?php
        namespace App\tests\controllers\admin;

        use App\Entity\Agency;
        use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
  
         // testing the routes regarding the agency entity
        class AdminAgencyControllerTest extends WebTestCase
        {
            private $client = null;
            private $admin; 
            public function setUp()
            {   
                 
                 $this->client = static::createClient();
                 // to call the login method
                $this->admin = self::bootKernel()->getContainer()->get('App\service\Setting'); 

            }
            public function testAgencyRoute()
            {   
                 $this->admin->logIn($this->client , $this);
                 $this->postAgency();
            }
            public function postAgency()
            {
                $data = ['agency_code' => '147D789abc',
                    'phone_number' => '0654789147',
                    'email' => 'agency@gmail.com',
                    'address' => 'algiers ouled fayet 14',
                    'city' =>array(
                        'id'=>1,
                        'name'=>'algiers'
                    )

                ];
                $this->client->request('POST', '/admin/agency', [], [], ['content_type' => 'Application/json'], json_encode($data));
                echo($this->client->getResponse()->getContent());
                $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
                // finding  the insert  agency from the db
                $agencyTable = static::$container->get('doctrine')->getManager()->getRepository( Agency::class) ;
                 
                $result = $agencyTable->findOneBy(['email' => $data['email'] , 
                'address' => $data['address']]);
                $this->assertNotEquals(null, $result);
                //making that the data inserted into the databse is the same as one in the input
                $this->assertEquals($data['email'] , $result ->getemail() );

            }

        }
