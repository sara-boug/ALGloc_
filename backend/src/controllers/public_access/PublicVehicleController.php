<?php
 namespace App\controllers\public_access; 
use App\Entity\Vehicle;
use App\service\RouteSettings;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\CallableUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Exception; 
use Symfony\Component\Routing\Annotation\Route ;
use Symfony\Component\HttpFoundation\StreamedResponse; 
use App\service\FileUploader;

// /public/vehicles     : description : getting the whole available vehicles               methods:GET
// (to be imporved ) /public/vehicles/agency/{id}     : description : getting the whole available vehicles  according to the agency      methods:GET
// (to be imporved) /public/vehicles/model/{id}       : description : getting the whole available vehicles  according to the  model      methods:GET
// /public/vehicle/{id} : description : selecting  a spricific vehicle   methods: GET

class PublicVehicleController extends AbstractController
{
    private $hateoas;
    public function __construct(SerializerInterface $serializer, RouterInterface $router)
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

    /** @Route("/public/vehicles" , name="get_vehicles" , methods={"GET"}) */
    public function get_vehicles_default(RouteSettings $rs): Response
    {
        try {
            $vehicles = $this->getDoctrine()->getRepository(Vehicle::class)->findAll();
            $vehiclesPag = $rs->pagination($vehicles, "get_vehicles");

            $vehiclesJson = $this->hateoas->serialize($vehiclesPag, 'json');
            return new Response($vehiclesJson, Response::HTTP_OK, ["Content-type" => "application\json"]);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

        }
    }

    /** @Route("/public/vehicles/agency/{id}" , name="get_vehicles_agency" , methods={"GET"}) */
    public function getVehiclesByAgency(int $id, RouteSettings $rs): Response
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

    /**  @Route("/public/vehicles/model/{id}" , name="get_vehicles_model" , methods={"GET"}) */
    public function getVehiclesByModel(int $id, RouteSettings $rs): Response
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

    /** @Route("/public/vehicle/{id}" , name="get_vehicle_by_id" , methods ={"GET"})  */
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

    
            /** @Route("/public/vehicle/{id}/image" , name="get_vehicle_image" , methods ={"GET"})  */
            public function getVehicleImage(int $id , FileUploader $uploader) 
            {
                try {
                    $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->findOneBy(['id' => $id]);
                    $vehicleImage = $vehicle->getImage();
                    $url =  '/image/'.$vehicleImage ;
                     $reponse=  new  StreamedResponse(   function()  use ( $uploader , $url) { 
                            $outputStream= fopen('php://output' , 'wb'); 
                            $stream = $uploader->readStream($url); 
                            stream_copy_to_stream( $stream , $outputStream );
                          }
                        );
                        ob_clean();
                    $reponse->headers->set('content-type' ,"image/png"); 
                    return $reponse;
                } catch (Exception $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }

            }

}
