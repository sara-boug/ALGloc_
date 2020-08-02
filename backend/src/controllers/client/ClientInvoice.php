<?php
   namespace App\controllers\client;
    use App\Entity\Invoice;
    use App\Repository\InvoiceRepository;
    use Exception;
    use App\service\RouteSettings;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Hateoas\HateoasBuilder; 
    use Hateoas\UrlGenerator\CallableUrlGenerator ; 
    use Symfony\Component\Routing\RouterInterface; 
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Routing\Annotation\Route; 
    use Symfony\Component\HttpFoundation\JsonResponse; 
    use Symfony\Component\HttpFoundation\Response; 

    class ClientInvoice extends AbstractController{ 
        private $hateoas; 
        public function __construct(  RouterInterface $router)
        {
            $this->hateoas= HateoasBuilder::create()
            ->setUrlGenerator(
                null , 
                new CallableUrlGenerator(function($route , array $parameter , $absolute) use ( $router ){ 
                        return $router->generate($route , $parameter , UrlGeneratorInterface::ABSOLUTE_URL);
                })
            )->build(); 

        }
        /** @Route( "/client/invoice/{id}" , name="get_invoice_client" , methods={"GET"}) */
        public function getInvoice(int $id ,  RouteSettings $routeSettings) : Response{ 
            try{ 
            $em = $this->getDoctrine()-> getManager(); 
            $clientInvoice = $em->getRepository(Invoice::class) -> findOneBy(['id' => $id]); 
            $client = $routeSettings ->getCurrentClient($em , $this->getUser());
            $invoiceContract=   $clientInvoice ->getContract(); 
            // checking that invoice belongs to the client
            if(!$invoiceContract->getclient()->getId()== $client->getid()) { 
                return new JsonResponse(["message" => "Not Found"], Response::HTTP_NOT_FOUND, ["Content-type" => "application\json"]);

            }
            // setting up the clients 
            $clientInvoice->setLink("get_invoice_client"); 
            $clientInvoice->getContract()->setLink("get_contract_client"); 
            $clientInvoice->getContract()->getClient()->setLink("get_client_profile"); 
            $clientInvoiceJson = $this->hateoas->serialize($clientInvoice , 'json'); 
            return new  Response( $clientInvoiceJson, Response::HTTP_OK, ["Content-type" => "application\json"]);


            }catch(Exception $e) { 
                 return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);


            }

        }

        /** @Route( "/client/invoices" , name="get_invoices_client" , methods={"GET"}) */
        public function getInvoices(InvoiceRepository $invoiceRepo , RouteSettings $routeSettings) : Response{ 
        try{  
            $em= $this->getDoctrine()->getManager();
            $client= $routeSettings->getCurrentClient($em ,$this->getUser() ); 
            $clientInvoices = $invoiceRepo->getClientInvoices($client->getid());
            foreach($clientInvoices as $clientInvoice) { 
                // setting up the necessary link for each element
                $clientInvoice->setLink("get_invoice_client"); 
                $clientInvoice->getContract()->setLink("get_contract_client"); 
                $clientInvoice->getContract()->getClient()->setLink("get_client_profile"); 
    
            }
            $clientInvoicesJson =$this->hateoas->serialize( $routeSettings->pagination($clientInvoices , "get_invoices_client")
                         , 'json'); 
            return new  Response( $clientInvoicesJson , Response::HTTP_OK, ["Content-type" => "application\json"]);
          
        }catch(Exception $e) { 
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);


       }

        }
    

    }




?> 