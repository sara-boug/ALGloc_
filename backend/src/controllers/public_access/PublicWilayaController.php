<?php
    namespace App\controllers\public_access;

        use App\service\RouteSettings;
        use Exception;
        use Hateoas\HateoasBuilder;
        use App\Entity\Wilaya; 
        use App\Entity\City; 
        use Hateoas\UrlGenerator\CallableUrlGenerator;
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\HttpFoundation\JsonResponse;
        use Symfony\Component\HttpFoundation\Response;
        use Symfony\Component\Routing\Annotation\Route;
        use Symfony\Component\Routing\RouterInterface;

        // /public/wilaya/{id}  :  description: modifying specific model             methods: GET
        // /public/wilayas      :  description:  selecting  models                   methods:  GET
        // /public/city/{id}    :  description: get model by category id             methods:  GET
        // /public/cities       :  description: get models by  brand id              methods:  GET

        class PublicWilayaController extends AbstractController
        {

            public function __construct(RouterInterface $router)
            {

                $this->hateoas = HateoasBuilder::create()
                    ->setUrlGenerator(
                        null,
                        new CallableUrlGenerator(function ($route, $parameters, $absolute) use ($router) {
                            return $router->generate($route, $parameters, RouterInterface::ABSOLUTE_URL);
                        })
                    )->build();
            }

            /** @Route("/public/wilaya/{id}" , name="get_wilaya" , methods={"GET"}) */
            public function getWilayaById(int $id ): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $wilaya = $this->em->getRepository(Wilaya::class)->findOneBy(['id' => $id]);
                    $wilayaJson = $this->hateoas->serialize($wilaya, 'json');
                    return new Response($wilayaJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route("/public/city/{id}" , name="get_city" , methods={"GET"}) */
            public function getCityById(int $id ): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $city = $this->em->getRepository(City::class)->findOneBy(['id' => $id]);
                    $cityJson = $this->hateoas->serialize($city, 'json');
                    return new Response($cityJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }
            /** @Route("/public/cities" , name="get_cities" , methods={"GET"}) */
            public function getCities(RouteSettings $rs): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $cities = $this->em->getRepository(City::class)->findAll();
                    $citiesJson = $this->hateoas
                        ->serialize($rs->pagination($cities, 'get_cities'), 'json');
                    return new Response($citiesJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route("/public/wilayas" , name="get_wilayas" , methods={"GET"}) */
            public function getwilayas(RouteSettings $rs): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $wilayas = $this->em->getRepository(wilaya::class)->findAll();
                    $wilayasJson = $this->hateoas
                        ->serialize($rs->pagination($wilayas, 'get_wilayas'), 'json');
                    return new Response($wilayasJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

        }
