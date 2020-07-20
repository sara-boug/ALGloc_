<?php
    namespace App\controllers\admin;

        use App\Entity\Client;
        use App\Entity\Contract_;
        use App\Entity\Invoice;
        use App\Entity\Vehicle;
        use App\service\RouteSettings;
        use DateInterval;
        use DateTime;
        use Doctrine\ORM\EntityManager;
        use Exception;
        use Hateoas\HateoasBuilder;
        use Hateoas\UrlGenerator\CallableUrlGenerator;
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\HttpFoundation\JsonResponse;
        use Symfony\Component\HttpFoundation\Request;
        use Symfony\Component\HttpFoundation\Response;
        use Symfony\Component\Routing\Annotation\Route;
        use Symfony\Component\Routing\RouterInterface;

        class AdminContractController extends AbstractController
        {

            private $hateoas;
            public function __construct(RouterInterface $router)
            {

                $this->hateoas = HateoasBuilder::create()
                    ->setUrlGenerator(
                        null,
                        new CallableUrlGenerator(
                            function ($route, $parameter, $absolute) use ($router) {
                                return $router->generate($route, $parameter, RouterInterface::ABSOLUTE_URL);
                            })
                    )->build();

            }
            private function checkdate($departure1, $arrival1, array $contracts)
            {
                // this function used to check whether the the vehicle is already linked to a contract
                foreach ($contracts as $c) {
                    if (($arrival1 > $c->getdeparture() && $arrival1 < $c->getarrival()) ||
                        ($departure1 > $c->getdeparture() && $departure1 < $c->getarrival())) {
                        return true;
                    }
                }
                return false;

            }
            ###################### FUNCTIONS USED IN ROUTES ########################################
            private function generateInvoice(Contract_ $contract, Vehicle $vehicle): Invoice
            {    
                 $diff =   $contract->getArrival()->diff(    $contract->getDeparture());
                $days = $diff->format('%d') + $diff->format('%m') ; 
                $cost = ($days * $vehicle->getRentalprice()) + $vehicle->getInssurancePrice() + $vehicle->getDeposit();
                $invoice = new Invoice();
                $invoice->setdate('now'); 
                $invoice->setamount($cost);
                $invoice->setpaid(false); // the invoice is initially set to non paid till the customer make the payment and the administrators modify the state
                $invoice->setcontract($contract);
                return $invoice;
            }
            public function jsonToContractObject($body, EntityManager $em): Contract_
            {
                $contract = new Contract_();
                $contract->setDate($body['date']);
                $contract->setArrival($body['arrival']);
                $contract->setDeparture($body['departure']);
                $client = $em->getRepository(Client::class)->findOneBy(['id' => $body["client"]["id"]]);
                $vehicle = $em->getRepository(Vehicle::class)->findOneBy(['id' => $body["vehicle"]["id"]]);
                $contract->setclient($client);
                $contract->setVehicle($vehicle);

                return $contract;

            }
            ################### ROUTES ####################################################

            /** @Route( "/admin/contract"  , name="post_contract" , methods ={"POST"} ) */
            public function postContract(Request $request)
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $body = json_decode($request->getContent(), true);
                    $contract = $this->jsonToContractObject($body, $em);
                    if ($contract->getDeparture() > $contract->getArrival()) {
                        return new JsonResponse(['message' => "departure can not be after the arrival"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                    }
                    $contracts = $em->getRepository(Contract_::class)->findBy(
                        ['vehicle' => $contract->getVehicle()->getid()]);

                    $dataTaken = $this->checkdate($contract->getDeparture(), $contract->getArrival(), $contracts);
                    if ($dataTaken) {
                        return new JsonResponse(['message' => "Vehicle Already Allocated at this period"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                    }
                    $em->persist($contract);
                    $em->flush();
                    // the invoice is automatically generated with the contracted
                    $invoice = $this->generateInvoice($contract, $contract->getVehicle());
                    $em->persist($invoice);
                    $em->flush();
                    $contractJson = $this->hateoas->serialize($contract, 'json');
                    dd($contractJson);
                    return new Response($contractJson, Response::HTTP_CREATED, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                     return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route( "/admin/contracts"  , name="get_contracts" , methods ={"GET"} ) */
            public function getContracts(RouteSettings $setting)
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $constracts = $em->getRepository(Contract_::class)->findAll();
                    $contractsJson = $this->hateoas->serialize(
                        $setting->pagination($constracts, "get_contracts")
                        , 'json'
                    );
                    return new Response($contractsJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route( "/admin/contract/{id}"  , name="get_contract" , methods ={"GET"} ) */
            public function getContractById(int $id)
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $contract = $em->getRepository(Contract_::class)->findOneBy(['id' => $id]);
                    $contractJson = $this->hateoas->serialize($contract, 'json');
                    return new Response($contractJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route( "/admin/contract/{id}"  , name="patch_contract" , methods ={"PATCH"} ) */
            public function PatchContractById(int $id, Request $request)
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $contract = $em->getRepository(Contract_::class)->findOneBy(['id' => $id]);
                    $body = json_decode($request->getContent(), true);
                    // in order to patch the arrival and departure  it shoulld'nt interfer  with another contract's departures and arrivals
                    if (isset($body["arrival"])) {
                        // checking whether the arrival
                        if ($contract->getdeparture() > $body["arrival"]) {
                            return new JsonResponse(['message' => "departure can not be after the arrival"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                        }
                        $contracts = $em->getRepository(Contract_::class)->findBy(['vehicle' => $contract->getVehicle()->getid(), 'id' != $id]);
                        if ($this->checkdate($contract->getdeparture(), $body["arrival"], $contracts)) {
                            return new JsonResponse(['message' => "can not extend the period"], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                        }

                    }

                    $contractJson = $this->hateoas->serialize($contract, 'json');
                    return new Response($contractJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

        }
