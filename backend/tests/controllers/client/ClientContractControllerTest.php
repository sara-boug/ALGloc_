<?php 
  namespace tests\controllers\client;

      use App\Entity\Contract_;
use App\Entity\Invoice;
use App\Repository\ClientRepository;
      use Symfony\Bundle\FrameworkBundle\Test\WebTestCase; 
      use App\Repository\Contract_Repository; 

 
      class  ClientContractControllerTest  extends WebTestCase   {  
         private $client ; 
         private $setting; 
         private $data; 
         private $id;
         private $contractId; 
         private $contractRepo; 
         private  $invoiceId; 
          // token used for the header 
         private $token ; 
          public function  setUp() :void  { 
            $this->client= static::createClient(); 
            $this->setting = self::bootKernel()->getContainer()->get('App\service\Setting'); 
            //signup the user 
             $this->setting->signUpClient($this->client , $this); 
            // this token generated when the user login
            $login =  $this->setting->loginClient($this->client , $this);
            $this->token= $login['token']; // token used to set up the header 
            $this->id=$login['id'];   // the id used to get the current user
         
         }
            // testing the signup route
         public function testShowPost(){ 
             $this->getClientProfile(); 
             $this->postContract(); 
             $this->patchContract();
             $this->getContracts(); 
             $this->getInvoice(); 
             $this->getInvoices();
            $this->deleteContract(); 
         
         }
         public function getClientProfile() { 
            // asserting that a client can access his own profile 
           $this->client ->request('GET' , '/client/'. $this->id. '/profile' 
           ,  [] ,[],['content_type' => 'Application/json' , 
                  'Authorization' => 'Bearer '.$this->token
                 ]);
 
          $this->assertEquals($this->client->getResponse()->getStatusCode() ,200); 

            // testing whether a client can access another user's profile
            $testId= $this->id+1;
            $this->client ->request('GET' , '/client/'. $testId . '/profile' 
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ]);

           $this->assertEquals($this->client->getResponse()->getStatusCode() , 404); 

         }
         public function postContract(){ 
             $this->data = [
               "date"=> 'now', 
               "departure"=> '1-11-2020',
               "arrival"=> '17-12-2020', 
               "vehicle" =>array('id' =>1) 
            ] ;   
            // setting up the header for the authorizition
             $this->client ->request('POST' , '/client/contract' 
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ],json_encode($this->data)
            ) ; 
            $this->contractId= ( json_decode($this->client->getResponse()->getContent() , true))['id']; 
           $this->assertEquals($this->client->getResponse()->getStatusCode() , 200); 
           // asserting that an invoice have been created  
           $em= static::$container->get('doctrine')->getManager() ;
            $invoice = $em->getRepository(Invoice::class)-> findBy(['contract_' => $this->contractId]);
            $this->invoiceId = $invoice[0]-> getid(); 
            $this->assertEquals(sizeof($invoice), 1); //since an invoice is generated automatically when a contract is generated
           
         }
         public function patchContract(){  
            $this->data = [
               "arrival"=> '18-12-2020', 
             ] ;   
            // setting up the header for the authorizition
             $this->client ->request('PATCH' , '/client/contract/'. $this->contractId
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ],json_encode($this->data)
            ) ; 
            $this->assertEquals($this->client->getResponse()->getStatusCode() , 200); 
 
         }
         public function getContracts(){
            $this->client ->request('GET' , '/admin/contracts' 
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ] );  
             // 401 since a client can not a access an admin ressource 
            $this->assertEquals($this->client->getResponse()->getStatusCode() , 401); 
            $this->client ->request('GET' , '/client/contracts' 
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ] );  
             // 401 since a client can not a access an admin ressource 
            $this->assertEquals($this->client->getResponse()->getStatusCode() , 200); 
         }
        // the purpose of the this function is to delete the contract adding it by the client 
        // in order not to overwhelm the  db
         public function deleteContract(){ 
            $em= static::$container->get('doctrine')->getManager() ;
            $contract= $em->getRepository( Contract_::class)->delete($this->contractId); 
 
            
         }
         // testing the invoice 
         public function getInvoice(){ 
            $this->client ->request('GET' , '/client/invoice/'.$this->invoiceId 
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ] );  
            $this->assertEquals($this->client->getResponse()->getStatusCode() , 200); 

          }
          // get invoices route
         public function getInvoices(){ 
            $this->client ->request('GET' , '/client/invoices' 
            ,  [] ,[],['content_type' => 'Application/json' , 
                   'Authorization' => 'Bearer '.$this->token
                  ] );  
                  dd($this->client->getResponse()->getContent());


             $this->assertEquals($this->client->getResponse()->getStatusCode() , 200); 

         }
      }

?> 