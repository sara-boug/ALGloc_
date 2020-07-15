<?php
  namespace App\controllers\admin;

    use App\Entity\Brand;
    use App\Entity\Category;
    use App\Entity\Model;
    use App\service\RouteSettings;
    use Doctrine\ORM\EntityManager;
    use Exception;
    use Hateoas\HateoasBuilder;
    use Hateoas\UrlGenerator\CallableUrlGenerator;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Routing\RouterInterface;

    // routes regarding Admin Model controller
    // /admin/model      :   description  : posting a specefic model                                 methods: POST
    // /admin/models     :    description : getting the whole available cities                       methods:GET
    // /admin/model/{id} :   description  : modifying, deleting or getting a specific Model by id    methods: GET , PATCH, DELETE
    // /admin/models/category/{id} : description :  selecting a model by a category   methods: GET 
    // /admin/models/brand/{id}    : description :  selecting a  model by a  brand    methods: GET 


    class AdminModelController extends AbstractController
    {

        private $hateoas;
        private $em;
        public function __construct(RouterInterface $router)
        {
            $this->hateoas = HateoasBuilder::create()
                ->setUrlGenerator(
                    null,
                    new CallableUrlGenerator(function ($route, array $parameter, $absolute) use ($router) {
                        return $router->generate($route, $parameter, UrlGeneratorInterface::ABSOLUTE_URL);
                    })
                )->build();

        }
        private function jsonToModelObject($body, EntityManager $em): Model
        {
            $model = new Model();
            $model->setName($body["name_"]);
            $brand = $em->getRepository(Brand::class)->findOneBy(['id' => $body["brand"]["id"]]);
             $category = $em->getRepository(Category::class)->findOneBy(['id' => $body["category"]["id"]]);
            $model->setbrand($brand);
            $model->setcategory($category);
            return $model;

        }

        /** @Route("/admin/model" , name="post_model" , methods={"POST"}) */
        public function postModel(Request $request): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();

                $body = json_decode($request->getContent(), true);
                $model = $this->jsonToModelObject($body, $this->em);
                $this->em->persist($model);
                $this->em->flush();
                $modelJson = $this->hateoas->serialize($model, 'json');
                return new Response($modelJson, Response::HTTP_CREATED  , ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST ,  ["Content-type" => "application\json"]);

            }

        }

        /** @Route("/admin/model/{id}" , name="get_model" , methods={"GET"}) */
        public function getModelById(int $id, Request $request): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $model = $this->em->getRepository(Model::class)->findOneBy(['id' => $id]);
                $modelJson = $this->hateoas->serialize($model, 'json');
                return new Response($modelJson, Response::HTTP_OK , ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }

        /** @Route("/admin/model/{id}" , name="patch_model" , methods={"PATCH"}) */
        public function patchModelById(int $id, Request $request): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $model = $this->em->getRepository(Model::class)->findOneBy(['id' => $id]);
                $body = json_decode($request->getContent(), true);
                // updating according to the available attribute
                if (isset($body['name_'])) {$model->setName($body['name_']);}
                if (isset($body['brand']["id"])) {$model->setBrand($body['brand']["id"]);}
                if (isset($body['category']["id"])) {$model->setBrand($body['category']["id"]);}
                $this->em->flush();
                $modelJson = $this->hateoas->serialize($model, 'json');
                return new Response($modelJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }

        /** @Route("/admin/model/{id}" , name="delete_model" , methods={"DELETE"}) */
        public function deleteModelById(int $id, Request $request): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $model = $this->em->getRepository(Model::class)->findOneBy(['id' => $id]);
                $this->em->remove($model);
                $this->em->flush();
                return new JsonResponse(['message' => "deleted successfully"], Response::HTTP_OK, ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }

        /** @Route("/admin/models" , name="get_models" , methods={"GET"}) */
        public function getModels(RouteSettings $rs): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $models = $this->em->getRepository(Model::class)->findAll();
                $modelsJson = $this->hateoas
                    ->serialize($rs->pagination($models, 'get_models'), 'json');
                return new Response($modelsJson, Response::HTTP_OK , ["Content-type" => "application\json"]) ;

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }

        /** @Route("/admin/models/brand/{id}" , name="get_models_by_brand" , methods={"GET"}) */
        public function getModelsByBrand( int $id , RouteSettings $rs): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $models = $this->em->getRepository(Model::class)->findBy(['brand' => $id ]);
                $modelsJson = $this->hateoas
                    ->serialize($rs->pagination($models, "get_models"), 'json');
                return new Response($modelsJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                dd($e); 
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }
        /** @Route("/admin/models/category/{id}" , name="get_models_by_category" , methods={"GET"}) */
        public function getModelsByCategory( int $id , RouteSettings $rs): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $models = $this->em->getRepository(Model::class)->findBy(['category' => $id ]);
                $modelsJson = $this->hateoas
                    ->serialize($rs->pagination($models, "get_models"), 'json');
                return new Response($modelsJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                 return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }


    }
