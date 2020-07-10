<?php
        namespace App\tests\controllers\admin;

        use App\Entity\Agency;
        use App\Entity\Vehicle;
        use App\Repository\VehicleRepository;
        use PHPUnit\Framework\Constraint\Count;
        use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
        use Symfony\Bundle\FrameworkBundle\KernelBrowser;
  
         // testing the routes regarding the agency entity
        class AdminAgencyControllerTest extends WebTestCase
        {   /** @var KernelBrowser */
            private $client = null  ;
            private $admin; 
            private $data ; 
            public function setUp()
            {   
                 
               $this->client = static::createClient();
                 // to call the login method
                $this->admin = self::bootKernel()->getContainer()->get('App\service\Setting'); 
                $this->data =['agency_code' => '147D789abc',
                        'phone_number' => '0654789147',
                        'email' => 'agency@gmail.com',
                        'address' => 'algiers ouled fayet 14',
                        'city' => array( 'id' => 1,'name' => 'algiers' )];


            }
            public function testAgencyRoute()
            {   
                 $this->admin->logIn($this->client , $this);
                 $this->post_agency();
                 $this->get_agencies(); 
                 $this->get_agency();
                 $this->get_agency_by_cityId();
               //  $this->patch_agency(); 
              //   $this->delete_agency(); 
            }
            public function post_agency()
            {
               

                $this->client->request('POST', '/admin/agency', [], [], ['content_type' => 'Application/json'], json_encode($this->data));
                $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
                // finding  the insert  agency from the db
                $agencyTable = static::$container->get('doctrine')->getManager()->getRepository( Agency::class) ;
                 
                $result = $agencyTable->findOneBy(['email' => $this->data['email'] , 
                'address' => $this->data['address']]);
                $this->assertNotEquals(null, $result);
                //making that the data inserted into the databse is the same as one in the input
                $this->assertEquals($this->data['email'] , $result ->getemail() );

            }

            public function get_agencies(){ 
                $this->client->request('GET', '/admin/agencies' , [] ,[],['content_type' => 'Application/json']); 
                $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); 
                // assserting that the array is not empty 
                $this->assertNotEmpty($this->client->getResponse()->getContent() ); 
                 
            } 

            public function get_agency(){ 
                $this->client->request('GET', '/admin/agency/1' , [] ,[],['content_type' => 'Application/json']); 
                 $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); 
                // assserting that the data was really updated 
                $result =json_decode(  $this->client->getResponse()->getContent(),true);
                // asserting that we are getting the real data
                $this->assertEquals($this->data['email'] , $result['email']); 

            }

            
            public function get_agency_by_cityId(){ 
                $this->client->request('GET', '/admin/agency/city/1' , [] ,[],['content_type' => 'Application/json']); 
                $result =  $this->client->getResponse()->getContent();

                $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); 
                // assserting that the data was really updated 
                // asserting that we are getting the real data
                $this->assertEquals(count($result) , 1 ); 

            }

            


            
            public function patch_agency(){ 
                $data =[ 'address' => 'algiers ouled fayet 18',
                          'city' => array( 'id' => 2,'name' => 'Boumerdes' )] ; 
                $this->client->request('PATCH', '/admin/agency/1' , [] ,[],['content_type' => 'Application/json'],json_encode($data)); 
                 $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); 
                // assserting that the data was really updated 
                $result =json_decode(  $this->client->getResponse()->getContent(),true);
                $this->assertEquals($data['address'] , $result['address']); 
            }

            public function delete_agency(){ 
 
                $this->client->request('delete', '/admin/agency/1' , [] ,[],['content_type' => 'Application/json']); 
                 $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); 
                // assserting that the data was really updated 
                $agencyTable = static::$container->get('doctrine')->getManager()->getRepository( Agency::class) ;
                $vehiculeTable = static::$container->get(VehicleRepository::class) ;
                
                $result =$vehiculeTable->findByAgencyId(1); 
                // asserting that the data  associated with the agency is really deleted
                $this->assertEquals(count($result) , 0); 
            }

        }
