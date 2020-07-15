<?php
    namespace App\controllers\admin;

        use App\Entity\Agency;
        use App\Entity\Model;
        use App\Entity\Vehicle;
        use App\service\FileUploader;
        use App\service\RouteSettings;
        use Exception;
        use Hateoas\HateoasBuilder;
        use Hateoas\UrlGenerator\CallableUrlGenerator;
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\HttpFoundation\JsonResponse;
        use Symfony\Component\HttpFoundation\Request;
        use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
        use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
        use Symfony\Component\Routing\RouterInterface;
        use Symfony\Component\Serializer\SerializerInterface;
        // routes regarding Admin Vehicle controller
        // /admin/vehicle      : description : posting a specefic vehicle                        methods: POST
        // /admin/vehicles     : description : getting the whole available vehicles               methods:GET
         
        // (to be imporved ) /admin/vehicles/agency/{id}     : description : getting the whole available vehicles  according to the agency      methods:GET
        // (to be imporved) /admin/vehicles/modely/{id}     : description : getting the whole available vehicles  according to the  model      methods:GET

        // /admin/vehicle/{id} : description : modifying, deleting or getting a spricig vehicle   methods: GET , PATCH, DELETE
        class AdminVehicleController extends AbstractController
        {
            private $serializer;
            private $hateoas;
             public function __construct(SerializerInterface $serializer, RouterInterface $router  )
            {
                $this->serializer = $serializer;

                $this->hateoas = HateoasBuilder::create()
                    ->setUrlGenerator(
                        null,
                        new CallableUrlGenerator(function ($route, array $parameters, $absolute) use ($router) {
                            return $router->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
                        })
                    )
                    ->build();
 
            }
            // transforming the json body into a vehicle object
            private function toVehicleObject($body): Vehicle
            {
                $vehicle = new Vehicle();
                $vehicle->setRegistrationNumber($body["registration_number"]);
                $vehicle->setRentalPrice($body["rental_price"]);
                $vehicle->setInssurancePrice($body["inssurance_price"]);
                $vehicle->setDeposit($body["deposit"]);
                $vehicle->setpassengernumber($body["passenger_number"]);
                $vehicle->setimage($body["image_"]);
                $vehicle->setState($body["state"]);
                $vehicle->setSuitcaseNumber($body["suitcase_number"]);
                $vehicle->setGearbox($body["gearbox"]);
                $vehicle->setStatus($body["status"]);

                $agency = $this->getDoctrine()->getRepository(Agency::class)->findOneBy(['id' => $body["agency"]["id"]]);
                $vehicle->setagency($agency);
                $model = $this->getDoctrine()->getRepository(Model::class)->findOneBy(['id' => $body["model"]["id"]]);
                $vehicle->setmodel($model);
                return $vehicle;
            }
            /**
             * @Route("/admin/vehicle" , name="post_vehicle" , methods={"POST"})
             */
            public function post_vehicle(Request $request, FileUploader $uploader): Response
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    // extracting the file from the request , the image is called car
                    $image = $request->files->get('car');
                    $vehicle = $this->toVehicleObject(json_decode($request->getContent(), true));
                    // using the file uplloader
                    $url = $uploader->uploadVehicleImage($image);
                    // setting the refference url
                    $vehicle->setImage($url);
                    $em->persist($vehicle);
                    $em->flush();
                    $vehicleJson = $this->hateoas->serialize($vehicle, 'json');
                    return new Response($vehicleJson, Response::HTTP_CREATED , ["Content-type" => "application\json"]);
                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST , ["Content-type" => "application\json"]);

                }
            }

            /** @Route("/admin/vehicles" , name="get_vehicles" , methods={"GET"}) */
            public function get_vehicles_default(RouteSettings $rs): Response
            {
                try {
                    $vehicles = $this->getDoctrine()->getRepository(Vehicle::class)->findAll();
                    $vehiclesPag = $rs-> pagination($vehicles, "get_vehicles");

                    $vehiclesJson = $this->hateoas->serialize($vehiclesPag, 'json');
                    return new Response($vehiclesJson, Response::HTTP_OK, ["Content-type" => "application\json"]);
                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }
            }
            /** @Route("/admin/vehicles/agency/{id}" , name="get_vehicles_agency" , methods={"GET"}) */
            public function getVehiclesByAgency(int $id , RouteSettings $rs): Response
            {
                try {
                    $vehicles = $this->getDoctrine()->getRepository(Vehicle::class)->findBy(['agency' => $id]);
                    $vehiclesPag = $rs->pagination($vehicles, "get_vehicles");
                    $vehiclesJson = $this->hateoas->serialize($vehiclesPag, 'json');
                    return new Response($vehiclesJson, Response::HTTP_OK, ["Content-type" => "application\json"]);
                } catch (Exception $e) {
                     return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }
            }

            /**  @Route("/admin/vehicles/model/{id}" , name="get_vehicles_model" , methods={"GET"}) */
            public function getVehiclesByModel(int $id , RouteSettings $rs): Response
            {
                try {
                    $vehicles = $this->getDoctrine()->getRepository(Vehicle::class)->findBy(['model' => $id]);
                    $vehiclesPag = $rs->pagination($vehicles, "get_vehicles");
                    $vehiclesJson = $this->hateoas->serialize($vehiclesPag, 'json');
                    return new Response($vehiclesJson, Response::HTTP_OK, ["Content-type" => "application\json"]);
                } catch (Exception $e) {

                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }
            }

            /** @Route("/admin/vehicle/{id}" , name="get_vehicle_by_id" , methods ={"GET"})  */
            public function getVehicleById(int $id): Response
            {
                try {
                    $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->findOneBy([
                        'id' => $id,
                    ]);
                    $vehicleJson = $this->hateoas->serialize($vehicle, 'json');
                    return new Response($vehicleJson, Response::HTTP_OK, ["Content-type" => "application\json"]);
                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }
            /** @Route("/admin/vehicle/{id}" , name="patch_vehicle_by_id" , methods ={"PATCH"})  */
            public function patchVehicleById(int $id, Request $request, FileUploader $uploader): Response
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $vehicle = $em->getRepository(Vehicle::class)->findOneBy(['id' => $id]);
                    $body = json_decode($request->getContent(), true);
                    // updaing each value accoriding to its avaialability in the body
                    if (isset($body["registration_number"])) {$vehicle->setRegistrationNumber($body["registration_number"]);}
                    if (isset($body["rental_price"])) {$vehicle->setRentalPrice($body["rental_price"]);}
                    if (isset($body["inssurance_price"])) {$vehicle->setInssurancePrice($body["inssurance_price"]);}
                    if (isset($body["deposit"])) {$vehicle->setDeposit($body["deposit"]);}
                    if (isset($body["passenger_number"])) {$vehicle->setpassengernumber($body["passenger_number"]);}
                    if ($request->files->get('car')) {
                        $url = $uploader->uploadVehicleImage($request->files->get('car'));
                        $vehicle->setimage($url);
                    }
                    if (isset($body["state"])) {$vehicle->setState($body["state"]);}
                    if (isset($body["suitcase_number"])) {$vehicle->setSuitcaseNumber($body["suitcase_number"]);}
                    if (isset($body["gearbox"])) {$vehicle->setGearbox($body["gearbox"]);}
                    if (isset($body["status"])) {$vehicle->setStatus($body["status"]);}
                    // saving the changes
                    $em->flush();
                    $vehicleJson = $this->hateoas->serialize($vehicle, 'json');
                    return new Response($vehicleJson, Response::HTTP_OK, ["Content-type" => "application\json"]);
                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route("/admin/vehicle/{id}/image" , name="get_vehicle_image" , methods ={"GET"})  */
            public function getVehicleImage(int $id , FileUploader $uploader) 
            {
                try {
                    $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->findOneBy(['id' => $id]);
                    $vehicleImage = $vehicle->getImage();
                    $url =  __DIR__. '/uploads/image/car.jpeg' ;
                    $file=  $uploader->readStream('/image/car.jpeg'); 
                     return new  StreamedResponse( base64_encode( $file)  , 200,   ["content-type" => "image/jpeg" ] )   ;
                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

            /** @Route("/admin/vehicle/{id}" , name="delete_vehicle_by_id" , methods ={"DELETE"})  */
            public function deleteVehicle(int $id): Response
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->findOneBy(['id' => $id]);
                    $em->remove($vehicle);
                    $em->flush();
                    return new JsonResponse(["message" => "deleted successfully"], Response::HTTP_OK, ["Content-type" => "application\json"]);
                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                }
            }

        }
