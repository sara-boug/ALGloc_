<?php
    namespace App\controllers\public_access;

        use App\Entity\model;
        use App\Entity\Brand; 
        use App\Entity\Category;
        use App\service\RouteSettings;
        use Exception;
        use Hateoas\HateoasBuilder;
        use Hateoas\UrlGenerator\CallableUrlGenerator;
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\HttpFoundation\JsonResponse;
        use Symfony\Component\HttpFoundation\Request;
        use Symfony\Component\HttpFoundation\Response;
        use Symfony\Component\Routing\Annotation\Route;
        use Symfony\Component\Routing\RouterInterface;

        // /public/model/{id}            :  description: modifying specific model             methods: GET
        // /public/models                :  description:  selecting  models                   methods:  GET
        // /public/models/category/{id}  :  description: get model by category id             methods:  GET
        // /public/models/brand/{id}     :  description: get models by  brand id              methods:  GET
        // /public/categories            :  description : getting the whole available categories           methods:GET
        // /public/category/{id}         :  description : getting a specific category by id                methods: GET ,
        // /public/brands                 :  description : getting the whole available brands               methods:GET
        // /public/brand/{id}             :  description : modifying, deleting or getting a specific brand by id    methods: GET

        class PublicModelController extends AbstractController
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

            /** @Route("/public/model/{id}" , name="get_model" , methods={"GET"}) */
            public function getModelById(int $id, Request $request): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $model = $this->em->getRepository(Model::class)->findOneBy(['id' => $id]);
                    $modelJson = $this->hateoas->serialize($model, 'json');
                    return new Response($modelJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route("/public/models" , name="get_models" , methods={"GET"}) */
            public function getModels(RouteSettings $rs): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $models = $this->em->getRepository(Model::class)->findAll();
                    $modelsJson = $this->hateoas
                        ->serialize($rs->pagination($models, 'get_models'), 'json');
                    return new Response($modelsJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route("/public/models/brand/{id}" , name="get_models_by_brand" , methods={"GET"}) */
            public function getModelsByBrand(int $id, RouteSettings $rs): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $models = $this->em->getRepository(Model::class)->findBy(['brand' => $id]);
                    $modelsJson = $this->hateoas
                        ->serialize($rs->pagination($models, "get_models"), 'json');
                    return new Response($modelsJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    dd($e);
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }
            /** @Route("/public/models/category/{id}" , name="get_models_by_category" , methods={"GET"}) */
            public function getModelsByCategory(int $id, RouteSettings $rs): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $models = $this->em->getRepository(Model::class)->findBy(['category' => $id]);
                    $modelsJson = $this->hateoas
                        ->serialize($rs->pagination($models, "get_models"), 'json');
                    return new Response($modelsJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route( "/public/category/{id}" , name="get_category" , methods={"GET"}) */
            public function getcategoryById(int $id): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $category = $this->em->getRepository(Category::class)->findOneBy(['id' => $id]);
                    $categoryJson = $this->hateoas->serialize($category, 'json');
                    return new Response($categoryJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route("/public/categories" , name="get_categories" , methods={"GET"}) */
            public function getCategories(RouteSettings $rs): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $categories = $this->em->getRepository(Category::class)->findAll();
                    $categoriesJson = $this->hateoas
                        ->serialize($rs->pagination($categories, 'get_categories'), 'json');
                    return new Response($categoriesJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route( "/public/brand/{id}" , name="get_brand" , methods={"GET"}) */
            public function getBrandById(int $id): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $brand = $this->em->getRepository(Brand::class)->findOneBy(['id' => $id]);
                    $brandJson = $this->hateoas->serialize($brand, 'json');
                    return new Response($brandJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route("/public/brands" , name="get_brands" , methods={"GET"}) */
            public function getbrands(RouteSettings $rs): Response
            {
                try {
                    $this->em = $this->getDoctrine()->getManager();
                    $brands = $this->em->getRepository(brand::class)->findAll();
                    $brandsJson = $this->hateoas
                        ->serialize($rs->pagination($brands, 'get_brands'), 'json');
                    return new Response($brandsJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

        }
