<?php
namespace App\controllers\admin;
    use App\Entity\Admin_;
  use Doctrine\ORM\ORMException;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route; 
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted; 
  
    use Symfony\Component\HttpFoundation\Response;
    use Exception; 
class AdminController extends AbstractController
{    
 

    // this function use to avoid repetition in each route
    public function response($body, $statusCode): Response
    {
        $response = new Response($body, $statusCode);
        $response->headers->set('content-type', 'Application/json');
        return $response;

    }

    /**
     * @Route("/admin/login" , name="admin_login" , methods ={"POST" })
     */
    public function login_post( ): Response
        {
           

                if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
                    return $this->json([
                        'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
                    ], 400);
                } else { 
                    return $this->response(json_encode(['message' => "login success"]) ,  Response::HTTP_OK);

                }
        
            
        }

    /**
     * @Route("/admin/main" , name="admin_main" , methods ={"GET" })
     */
    // target page after the logouts
    public function main_get( Request $request): Response
        {
            try {       

                return $this->response(json_encode(['message' => "success" , $request->getContent()]) ,  Response::HTTP_OK);
            } catch (Exception $e) {
                echo ($e);
                $exception = array("error" => $e->getMessage());
                return $this->response(json_encode($exception), Response::HTTP_BAD_REQUEST);

            }
            
        }
    
        /**
         * @Route("/admin/logout" , name="admin_logout" , methods ={"GET"})
         
         */
        public function logout_get( ): Response
            {
                try {               
                    return $this->response(json_encode(['message' => "login success"]) ,  Response::HTTP_OK);
                } catch (ORMException $e) {
                    echo ($e);
                    $exception = array("error" => $e->getMessage());
                    return $this->response(json_encode($exception), Response::HTTP_BAD_REQUEST);

                }
            }


        /**
         * @Route("/admin/agency" , name="add_Agency" , methods ={"GET"})
          * @IsGranted("ROLE_ADMIN")
         */
        public function get_agency( ): Response
        {
            try {         
      
                return $this->response(json_encode(['message' => "login success"]) ,  Response::HTTP_OK);
            } catch (Exception $e) {
                echo ($e);
                $exception = array("error" => $e->getMessage());
                return $this->response(json_encode($exception), Response::HTTP_BAD_REQUEST);

            }
        }


}
