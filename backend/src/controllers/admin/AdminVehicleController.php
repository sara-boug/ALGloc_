<?php
    namespace App\controllers\admin;

        use App\Entity\Vehicle;
        use App\service\FileUploader;
        use App\Entity\Agency; 
        use App\Entity\Model; 
        use App\Entity\City;
        use App\Entity\Wilaya;
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
            private $hateoas;
             public function __construct(SerializerInterface $serializer)
            {
                $this->serializer = $serializer;
                $this->hateoas = HateoasBuilder::create()->build();

            }
            // transforming the json body into a vehicle object
            private function toVehicleObject($body): Vehicle
            {
                $vehicle = new Vehicle();
                $vehicle ->setRegistrationNumber($body["registration_number"]) ;
                $vehicle->setRentalPrice($body["rental_price"]) ;
                $vehicle->setInssurancePrice($body["inssurance_price"]) ;
                $vehicle->setDeposit($body["deposit"]);
                $vehicle->setpassengernumber($body["passenger_number"]);
                $vehicle->setimage( $body["image_"]);
                $vehicle ->setState($body["state"]); 
                $vehicle->setSuitcaseNumber($body["suitcase_number"]);
                $vehicle->setGearbox($body["gearbox"]);
                $vehicle->setStatus($body["status"]);
                //finding the agency and the model through their idsthat are provided by hte request 
                
                $agency=$this->getDoctrine()->getRepository(Agency::class)->findOneBy(['id' =>$body["agency"]["id"]] );
                $agencycity= $this->getDoctrine()->getRepository(City::class)->findOneBy(['id' =>$agency->getCity()->getid()]);
                $agencycityWilaya= $this->getDoctrine()->getRepository(Wilaya::class)->findOneBy(['id'=>$agencycity->getid()]); 
                $vehicle->setagency($agency) ;
                $vehicle->getagency()->setCity($agencycity);  
                $vehicle->getagency()->getCity()->setWilaya($agencycityWilaya); 
                $model= $this->getDoctrine()->getRepository(Model::class)->findOneBy(['id' =>$body["model"]["id"]]);
                $vehicle->setmodel($model); 
                 return $vehicle;
            }
            /**
             * @Route("/admin/vehicle" , name="post_vehicle" , methods={"POST"})
             */
            public function post_vehicle(Request $request, FileUploader $uploader): Response
            {        
                    $em= $this->getDoctrine()->getManager();
                     // extracting the file from the request , the image is called car 
                    $image= $request->files->get('car'); 
                    $vehicle = $this->toVehicleObject(json_decode( $request->getContent() , true)); 

                    // using the file uplloader 
                    $url =$uploader->uploadVehicleImage($image);
                    // setting the refference url 
                    $vehicle->setImage($url);
                      $em->persist($vehicle);
                     $em->flush();
                     $vehicleJson = $this ->hateoas->serialize($vehicle  , 'json'); 
                      dd($vehicleJson); 
                     return new JsonResponse(  $vehicleJson, Response::HTTP_OK);

                }  
           
        }
