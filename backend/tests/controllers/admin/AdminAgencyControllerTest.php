<?php
namespace App\tests\controllers\admin;
use App\tests\controllers\admin\AdminControllerTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// testing the routes regarding the agency entity
class AdminAgencyControllerTest extends WebTestCase
{
    private $client = null;
    public function setUp()
    {
        $this->client = static::createClient();

    }
    public function testAgencyRoute()
    {    $this->logIn();
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
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        // finding  the insert  agency from the db
        $agencyTable = static::$container->get('doctrine')->getManager();

        $result = $agencyTable->findOneBy(['email' => $data['email']]);
        $this->assertNotEquals(null, $result);

    }

    protected function logIn()
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
