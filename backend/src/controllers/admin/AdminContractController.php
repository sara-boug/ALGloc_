<?php
  namespace App\controllers\admin;

    use App\Entity\Client;
    use App\Entity\Contract_;
    use App\Entity\Vehicle;
    use Doctrine\ORM\EntityManager;
    use Exception;
    use Hateoas\HateoasBuilder;
    use Hateoas\UrlGenerator\CallableUrlGenerator;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\RouterInterface;
    use Symfony\Component\Routing\Annotation\Route; 
    use Symfony\Component\HttpFoundation\JsonResponse;

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
        /** @Route( "/admin/contract"  , name="post_contract" , methods ={"POST"} ) */
        public function postContract(Request $request)
        {
            try {
                $em = $this->getDoctrine()->getManager();
                $body = json_decode($request->getContent(), true);
                $contract = $this->jsonToContractObject($body, $em);
                $contractJson = $this->hateoas->serialize($contract, 'json');
                return new Response($contractJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }

    }
