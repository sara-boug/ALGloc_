<?php
    namespace App\controllers\admin;

        use App\Entity\Agency;
        use App\Entity\Model;
        use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
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
        // (to be imporved) /admin/vehicles/model/{id}     : description : getting the whole available vehicles  according to the  model      methods:GET

        // /admin/vehicle/{id} : description : modifying, deleting or getting a spricig vehicle   methods: GET , PATCH, DELETE
        class AdminVehicleController extends AbstractController
        {
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
                 
                    // setting the url of the image uploader
                    $vehicle->setImage($uploader->uploadVehicleImage($image));
                    $em->persist($vehicle);
                    $em->flush();
                    $vehicleJson = $this->hateoas->serialize($vehicle, 'json');
                    return new Response($vehicleJson, Response::HTTP_CREATED , ["Content-type" => "application\json"]);
                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST , ["Content-type" => "application\json"]);

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
                        $uploader->deleteImage($vehicle->getImage()); 
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


            /** @Route("/admin/vehicle/{id}" , name="delete_vehicle_by_id" , methods ={"DELETE"})  */
            public function deleteVehicle(int $id , VehicleRepository $vehicleRepo) : Response
            {
                try {
                    $vehicleRepo->delete($id); 
                    return new JsonResponse(["message" => "deleted successfully"], Response::HTTP_OK, ["Content-type" => "application\json"]);
                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
                }
            }

        }
