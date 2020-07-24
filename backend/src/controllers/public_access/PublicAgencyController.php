<?php
namespace App\controllers\public_access;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route; 
    use Symfony\Component\Routing\RouterInterface ; 
    use Hateoas\HateoasBuilder ; 
    use App\Entity\Agency;
    use Hateoas\UrlGenerator\CallableUrlGenerator ; 
    use Symfony\Component\HttpFoundation\Response; 
    use App\service\RouteSettings; 
    use Symfony\Component\HttpFoundation\JsonResponse; 
    use App\Repository\AgencyRepository ; 
    use  Exception; 
    // /public/agencies   :  description: modifying specific agency   methods: GET
    // /public/agency/{id}    :  description: select agency by id   methods: GET
    // /public/agency/city/id :  description: get agency by city name     methods:  GET

    class PublicAgencyController extends AbstractController { 

        public function __construct( RouterInterface $router)
        {
            $this->hateoas = HateoasBuilder::create() 
            ->setUrlGenerator(
                null , 
                new CallableUrlGenerator(function($route , $parameters , $absolute) use ($router) { 
                    return $router->generate($route , $parameters , RouterInterface::ABSOLUTE_URL); 
                })
            )->build(); 
        }


        /**
         * @Route("/public/agencies" , name="get_agencies" , methods={"GET"})
         */
        public function get_agencies(RouteSettings $setting): Response
        {
            try {
                $em= $this->getDoctrine()->getManager(); 
                $agencies = $em->getRepository(Agency::class)->findAll();
                $jsonAgencies =$this->hateoas->serialize( $setting->pagination($agencies ,"get_agencies" ), 'json'  ) ;        
                return new Response($jsonAgencies, Response::HTTP_OK, ["Content-type" => "application\json"]);

            } catch (Exception $e) {
                return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

            }
        } 
        
            /**
             * @Route("/public/agency/{id}" , name ="get_agency" , methods={"GET"})
             */
            public function get_agency(int $id):Response {
                try { 
                $em= $this->getDoctrine()->getManager(); 
                $agency= $em->getRepository(Agency::class)->find($id);
                $jsonAgency = $this->hateoas->serialize($agency , 'json'); 
                return new Response( $jsonAgency,Response::HTTP_OK, ["Content-type" => "application\json"]);
                }catch( Exception $e){ 
                 dd($e); 
                return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);

                }
            }
            /** 
             * @Route("/public/agency/city/{id}" , name="get_agency_by_cityId" , methods ={"GET"})
             */
            public function  get_agency_by_cityId(int $id , AgencyRepository $agencyRepo ,RouteSettings $setting):Response { 
                try { 
                $agencies=$agencyRepo->findByCityId($id); 
                $jsonAgencies =$this->hateoas->serialize( $setting->pagination($agencies ,"get_agencies" ), 'json'  ) ;        
                return new Response($jsonAgencies, Response::HTTP_OK, ["Content-type" => "application\json"]);
    
                }catch( Exception $e){ 
                return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_BAD_REQUEST, ["Content-type" => "application\json"]);
            }
    
        }
    }
 


    ?> 