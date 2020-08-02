<?php
   namespace App\service;

        use App\Entity\Client;
        use App\Entity\Contract_;
        use App\Entity\Invoice;
        use Doctrine\ORM\EntityManager;
        use DateTime;
        use App\Entity\Vehicle;
        use App\Repository\Contract_Repository;
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\HttpFoundation\Request;
        use Symfony\Component\HttpFoundation\JsonResponse;
        use Symfony\Component\HttpFoundation\Response; 
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

  
            public function contractCheckDate($departure1, $arrival1, array $contracts)
            {
                // this function used to check whether the the vehicle is already linked to a contract
                foreach ($contracts as $c) {
                    if (
                        ($arrival1 > $c->getdeparture() && $arrival1 < $c->getarrival()) ||
                        ($departure1 > $c->getdeparture() && $departure1 < $c->getarrival()) &
                        ($c->getCancelled() ==false)) { // ensuring that the contract is not cancelled
                        return true;
                    }
                    
                }
                return false;

            }
 
            public function patchContractArrival( Contract_ $contract,EntityManager $em 
            , Contract_Repository $contractRepo , int $id  , $body ) :Contract_{ 
                // selecting the whole contracts related to the vehicle except the current one which is the id's 
                $contracts = $contractRepo->selectExcept(  $id , $contract->getVehicle()->getid());

                    // in order to patch the arrival and departure  it shoulld'nt interfer  with another contract's departures and arrivals
                    if (isset($body["arrival"])) {
                        // checking whether the arrival
                        if ( ( new DateTime( $body["arrival"] ) ) < $contract->getdeparture() ) {
                            return new JsonResponse(['message' => "departure can not be after the arrival"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                        }
                        if ($this->contractCheckDate($contract->getdeparture(), $body["arrival"], $contracts)) {
                            return new JsonResponse(['message' => "can not extend the period"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                        }
                       // case both of the conditions are false then a new invoice is generated
                       // the invoice is generated according to the number of days added 
                       // the previous arrival and the newly updated arrival 
                       // diff= Arrival2 - Arrival1
                        $invoice=   $this->generateInvoice($contract->getArrival() , 
                          new DateTime($body["arrival"] ), $contract  );   
                        $em->persist($invoice); 
                        $em->flush(); 
                  
                    } 

                  return $contract ;        

            }


        }
