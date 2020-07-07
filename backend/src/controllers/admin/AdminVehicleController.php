<?php
    namespace App\controllers\admin;

        use App\Entity\Vehicle;
        use App\service\FileUploader;
        use Exception;
        use Hateoas\HateoasBuilder;
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\HttpFoundation\JsonResponse;
        use Symfony\Component\HttpFoundation\Request;
        use Symfony\Component\HttpFoundation\Response;
        use Symfony\Component\Routing\Annotation\Route;
        use Symfony\Component\Serializer\SerializerInterface;
        use Symfony\Component\HttpFoundation\File\UploadedFile;

        // routes regarding Admin Vehicle controller
        // /admin/vehicle      : description : posting a specefic vehicle                        methods: POST
        // /admin/vehicles     : description : getting the whole available vehicles               methods:GET
        // /admin/vehicle/{id} : description : modifying, deleting or getting a spricig vehicle   methods: GET , PATCH, DELETE
        class AdminVehicleController extends AbstractController
        {
            private $serializer;
            public function __construct(SerializerInterface $serializer)
            {
                $this->serializer = $serializer;
            }
            // transforming the json body into a vehicle object
            private function toVehicleObject($body): Vehicle
            {
                $vehicle = new Vehicle();
                //   $vehicle ->setregistration_number($body["registration_number"]) ;
                //       $vehicle->setrental_price($body["rental_price"]) ;
                $vehicle->setdeposit($body["deposit"]);
                //     $vehicle->setpassenger_number($body["passenger_number"]);
                /* $vehicle->setimage_( $body["image_"]);
                $vehicle->setsuitcase_number($body["suitcase_number"]);
                $vehicle->setgearbox($body["gearbox"]);
                $vehicle->setstatus_($body["status"]);
                //finding the agency and the model through their ids
                $agency=$this->getDoctrine()->getRepository(Agency::class)->findOneBy(['id' =>$body["agency"]["id"]]);
                $model= $this->getDoctrine()->getRepository(Model::class)->findOneBy(['id' =>$body["model"]["id"]]);
                /*   $vehicle->setagency($agency);
                $vehicle->setmodel($model); */
                return $vehicle;

            }
            /**
             * @Route("/admin/vehicle" , name="post_vehicle" , methods={"POST"})
             */
            public function post_vehicle(Request $request, FileUploader $uploader): Response
            {
                try {
                    //dd($request->getContent());
                    $image= new UploadedFile( 'tests\imageFolderTest\car.jpeg' , 'car'  , 'image/jpeg' , null); 
                    $vehicle = $this->serializer->deserialize(
                        $request->getContent(),
                        Vehicle::class,
                        'json'
                    );
                     $url =$uploader->uploadVehicleImage($image);
                    $vehicle->setImage($url);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($vehicle);
                    $em->flush();
                    // $json=$hateoas->serialize($vehicle, 'json');
                    return new JsonResponse("json", Response::HTTP_OK);

                } catch (Exception $e) {
                    echo($e->getMessage());
                    return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);

                }

            }
        }
