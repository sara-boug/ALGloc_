<?php
  namespace App\controllers\admin;

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
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Routing\RouterInterface;

    // routes regarding Admin category controller
    // /admin/category      : description : posting a specefic  category                       methods: POST
    // /admin/categories     : description : getting the whole available categories              methods:GET
    // /admin/category/{id} : description : modifying, deleting or getting a specific category by id    methods: GET , PATCH, DELETE

    class AdminCategoryController extends AbstractController
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
        private function jsonToCategoryObject($body): Category
        {
            $category = new Category();
            $category->setName($body["name_"]);
            return $category;
        }

        /** @Route("/admin/category" , name="post_category" , methods={"POST"}) */
        public function postCategory(Request $request): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();

                $body = json_decode($request->getContent(), true);
                $category = $this->jsonTocategoryObject($body, $this->em);
                $this->em->persist($category);
                $this->em->flush();
                $categoryJson = $this->hateoas->serialize($category, 'json');
                return new Response($categoryJson, Response::HTTP_CREATED, ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                 return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }

        /** @Route( "/admin/category/{id}" , name="get_category" , methods={"GET"}) */
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

        /** @Route("/admin/category/{id}" , name="patch_category" , methods={"PATCH"}) */
        public function patchcategoryById(int $id, Request $request): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $category = $this->em->getRepository(Category::class)->findOneBy(['id' => $id]);
                $body = json_decode($request->getContent(), true);
                // updating according to the available attribute
                if (isset($body['name_'])) {$category->setName($body['name_']);}
                $this->em->flush();
                $categoryJson = $this->hateoas->serialize($category, 'json');
                return new Response($categoryJson, Response::HTTP_OK, ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }

        /** @Route("/admin/category/{id}" , name="delete_category" , methods={"DELETE"}) */
        public function deletecategoryById(int $id, Request $request): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $category = $this->em->getRepository(Category::class)->findOneBy(['id' => $id]);
                $this->em->remove($category);
                $this->em->flush();
                return new JsonResponse(['message' => "deleted successfully"], Response::HTTP_OK, ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }

        }

        /** @Route("/admin/categories" , name="get_categories" , methods={"GET"}) */
        public function getCategories(RouteSettings $rs): Response
        {
            try {
                $this->em = $this->getDoctrine()->getManager();
                $categories = $this->em->getRepository(Category::class)->findAll();
                $categoriesJson = $this->hateoas
                    ->serialize($rs->pagination($categories, 'get_categories'), 'json');
                return new Response($categoriesJson, Response::HTTP_OK , ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST , ["Content-type" => "application\json"]);

            }

        }

    }
