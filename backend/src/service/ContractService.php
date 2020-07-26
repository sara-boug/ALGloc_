<?php
   namespace App\service;

        use App\Entity\Client;
        use App\Entity\Contract_;
        use App\Entity\Invoice;
        use Doctrine\ORM\EntityManager;
        use DateTime;
        use App\Entity\Vehicle;
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// this class containes some functionalities related to the contract route
        // both for the admin route and the client route

        class ContractService
        {

            public function generateInvoice(DateTime $departure, DateTime $arrival, Contract_ $contract): Invoice
            {
                $diff = $arrival->diff($departure);
                $days = $diff->format('%d') + $diff->format('%m'); // summing up the months and the days to obtain the day's difference
                $cost = ($days * $contract->getVehicle()->getRentalprice()) + $contract->getVehicle()->getInssurancePrice() + $contract->getVehicle()->getDeposit();
                $invoice = new Invoice();
                $invoice->setdate('now');
                $invoice->setamount($cost);
                $invoice->setpaid(false); // the invoice is initially set to non paid till the customer make the payment and the administrators modify the state
                $invoice->setcontract($contract);
                return $invoice;
            }
            // the admin function is slightly different because of the client part 
            public function  JsonToContractObjectAdmin($body, EntityManager $em ){ 
              $client = $em->getRepository(Client::class)->findOneBy(['id' => $body["client"]["id"]]);
              return $this->JsonToContractObject($body,$em ,$client );
            }

            public  function  JsonToContractObject($body, EntityManager $em ,Client $client ): Contract_
            {
                $contract = new Contract_();
                $contract->setDate($body['date']);
                $contract->setArrival($body['arrival']);
                $contract->setDeparture($body['departure']);
                
                $vehicle = $em->getRepository(Vehicle::class)->findOneBy(['id' => $body["vehicle"]["id"]]);
                $contract->setclient($client);
                $contract->setVehicle($vehicle);
                return $contract;

            }

  

        }
