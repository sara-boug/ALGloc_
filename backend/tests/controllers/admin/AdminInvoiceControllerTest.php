<?php

namespace tests\controllers\admin;

use App\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdmininvoiceControllerTest extends WebTestCase
{
    private $admin;
    private $client;
    private $data;
    private $id;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->admin = self::bootKernel()->getContainer()->get('App\service\Setting');

    }

    public function testinvoiceRoute()
    {
        $this->admin->logIn($this->client, $this);
        $this->postInvoice(); 
        $this->getInvoices(); 
        $this->getInvoice(); 
        $this->patchInvoice();
        $this->deleteInvoice(); 

    }

    public function postInvoice()
    {   // dates generally are strings and would be ocnverted to  DateTime Object in the setter 
        $this->data = [
            "date" => 'now' , 
            "amount" => '3000',
            "paid" => false , 
            "contract" => array("id" => 1) 
        ];
        $this->client->request('POST', '/admin/invoice', [], [], ['Content-type' => 'Application/json'],  json_encode($this->data));
        $this->id = (json_decode($this->client->getResponse()->getContent(), true))['id'];
        
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 201 );

    }

     // invoices selection
    public function getInvoices(){ 
        $contractId= 1 ;
        $this->client->request('GET', '/admin/invoices', [], [], ['Content-type' => 'Application/json'],  json_encode($this->data));
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200 );
        // selecting invoices by contract Id
        $this->client->request('GET', '/admin/invoices/contract/'.$contractId, [], [], ['Content-type' => 'Application/json'],  json_encode($this->data));
        $this->assertEquals($this->client->getResponse()->getStatusCode(), 200 );

    }


         // test : selecting the invoice by Id
         public function getInvoice(){ 
           
            $this->client->request('GET', '/admin/invoice/'.$this->id, [], [], ['Content-type' => 'Application/json'],  json_encode($this->data));
             $this->assertEquals($this->client->getResponse()->getStatusCode(), 200 );
          }

          public function  patchInvoice()
          {   // dates generally are strings and would be ocnverted to  DateTime Object in the setter 
              $this->data = [ 
                  "amount" => '-3000',
              ];
              $this->client->request('PATCH', '/admin/invoice/'.$this->id, [], [], ['Content-type' => 'Application/json'],  json_encode($this->data));
              $this->assertEquals($this->client->getResponse()->getStatusCode(), 400 );
              echo($this->client->getResponse()->getContent()); 

            }

            public function  deleteInvoice(){ 
           
                $this->client->request('DELETE', '/admin/invoice/'.$this->id, [], [], ['Content-type' => 'Application/json'],  json_encode($this->data));
                 $this->assertEquals($this->client->getResponse()->getStatusCode(), 200 );
                 // asserting that the invoice isno longer available in the table
                 $invoice = static::$container->get('doctrine')->getManager()
                 ->getRepository(Invoice::class)->findOneBy(['id' => $this->id]); 
               
                 $this->assertEquals($invoice , null); 
                 
              }
    }
