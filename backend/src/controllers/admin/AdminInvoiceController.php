<?php
namespace App\controllers\admin;

use App\Entity\Contract_;
use App\Entity\Invoice;
use App\service\RouteSettings;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\RouterInterface ; 
use Hateoas\HateoasBuilder; 
use Hateoas\UrlGenerator\CallableUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\HttpFoundation\Response;

class AdminInvoiceController  extends AbstractController { 
//  routes regarding the invoice Controller
// /admin/invoice           :  description: add one invoice                        methods: post
// /admin/invoices          :  description: iterating through all the invoices     methods: get
// /admin/invoice/{id}      :  description: modifying specific invoice             methods :Patch, Delete , get
// /admin/invoice/client/id :  description: get invoice by city name               methods:  GET

 
    private $hateoas; 
    public function __construct( RouterInterface $router)
    {
        $this->hateoas = HateoasBuilder::create() 
        ->setUrlGenerator(
            null , 
            new CallableUrlGenerator(function($route , $parameters , $absolute) use ($router) { 
                return $router->generate($route , $parameters , RouterInterface::ABSOLUTE_URL); 
            })
        )->build(); 
    }
      // converting the json object into 
     private function jsonToInvoiceObject($body , EntityManager  $em):Invoice
     {  

         $invoice = new Invoice(); 
         $invoice->setdate($body["date"]); 
         $invoice->setamount($body["amount"]); 
         $invoice->setpaid($body["paid"]); 
         //getting the contract object from the DB
         $contract =$em->getRepository(Contract_::class)->findOneBy(['id' => $body["contract"]["id"]]);
         // setting up the contract object 
         $invoice->setcontract($contract); 
         return $invoice ; 

     }
    
     /**  @Route("/admin/invoice"  , name="post_invoice" , methods={"POST"}) */
    public function postInvoice(Request $request ):Response
    { 
         try { 
             $em= $this->getDoctrine()->getManager(); 
             $body = json_decode($request->getContent() , true );  //request body
             $invoice=$this->jsonToInvoiceObject($body , $em); 
             $em->persist($invoice) ; 
             $em->flush(); 
             $invoiceJson= $this->hateoas->serialize($invoice , 'json'); 
              return new Response( $invoiceJson, Response::HTTP_CREATED , ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                 return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
    

         }
     }


     /**  @Route("/admin/invoices"  , name="get_invoices" , methods={"GET"}) */
      public function  getInvoices(  RouteSettings $setting):Response
      { 
        try{ 
            $em= $this->getDoctrine()->getManager(); 
             $invoicesJson= $this->hateoas->serialize(
             $setting->pagination(
             $em->getRepository(Invoice::class)->findAll() , 'get_invoices') , 'json') ; 
             return new Response( $invoicesJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

        }catch( Exception $e ) { 
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

        }
     

      }    
           // consistes of selecting the invoices according to the contracts 
           /**  @Route("/admin/invoices/contract/{id}"  , name="get_invoice_by_contract_Id" , methods={"GET"}) */
           public function  getInvoicesByContractId( int $id ,   RouteSettings $setting):Response
           { 
             try{ 
                 $em= $this->getDoctrine()->getManager(); 
                 $invoicesJson= $this->hateoas->serialize(
                  $setting->pagination(
                   $em->getRepository(Invoice::class)->findBy(['contract_'=>$id])
                  , 'get_invoices') 
                  , 'json') ; 
                  return new Response( $invoicesJson, Response::HTTP_OK , ["Content-type" => "application\json"]);
     
             }catch( Exception $e ) { 
                 return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
     
             }
          
     
           }


     /**  @Route("/admin/invoice/{id}"  , name="get_invoice" , methods={"GET"}) */
      public function  getInvoice(  int $id):Response
      { 
        try{ 
            $em= $this->getDoctrine()->getManager(); 
             $invoiceJson= $this->hateoas->serialize(    
             $em->getRepository(Invoice::class)->findOneBy(['id'=> $id]),
              'json') ; 
             return new Response( $invoiceJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

        }catch( Exception $e ) { 
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
        }

    }

         /**  @Route("/admin/invoice/{id}"  , name="patch_invoice" , methods={"PATCH"}) */
         public function  patchInvoice(  int $id , Request $request):Response
         { 
           try{ 
               $em= $this->getDoctrine()->getManager(); 
               $body = json_decode($request->getContent() , true );  //request body
               $invoice=  $em->getRepository(Invoice::class)->findOneBy(['id'=> $id]) ; 
                if(isset($body["paid"])) { $invoice->setpaid($body["paid"]); }
                if(isset($body["amount"])) 
                    {
                     if($body["amount"] <0 ) { 
                        return new JsonResponse(["error" => "illegal amount value" ], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                     }
                      $invoice->setamount($body["amount"]);
                     }

                $em->flush(); 
                $invoiceJson= $this->hateoas->serialize($invoice  , 'json'); 
                return new Response( $invoiceJson, Response::HTTP_OK, ["Content-type" => "application\json"]);
   
           }catch( Exception $e ) { 
               return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
           }
   
       }
     
         /**  @Route("/admin/invoice/{id}"  , name="delete_invoice" , methods={"DELETE"}) */
        public function  deleteInvoice(  int $id):Response
         {  
            try{
                $em= $this->getDoctrine()->getManager(); 
                $invoice=  $em->getRepository(Invoice::class)->findOneBy(['id'=> $id]) ;
                $em->remove($invoice); 
                $em->flush(); 
                return new JsonResponse(["message" => "deleted successfully"], Response::HTTP_OK, ["Content-type" => "application\json"]);
            }catch( Exception $e ) 
            { 
                return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
            }

        }
       

    }


?> 