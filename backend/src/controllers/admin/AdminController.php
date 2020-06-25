<?php
use App\security\AdminAuth;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Admin_;

class AdminController extends AbstractController
{
    private $auth;
    public function __construct(AdminAuth $auth)
    {
        $this->auth = $auth;
    }
    // this function use to avoid repetition in each route
    public function response($body, $statusCode): Response
    {
        $response = new Response($body, $statusCode);
        $response->headers->set('content-type', 'Application/json');
        return $response;

    }

    /**
     * @Route("/admin/login" , name="admin_login_path" , methods ={"POST"})
     */
    public function login(Request $request, JWTTokenManagerInterface $generateToken): Response
    {
        try {
            // in order to get user repos
            $repos = $this->getDoctrine()->getRepository(Admin_::class);
            $entityManager = $this->getDoctrine()->getManager();
            // extracting the body content
            $body = json_decode($request->getContent(), true);
            if (!empty($body['email']) && !empty($body['password'])) {
                $admin = $repos->findOneBy(['email' => $body['email']]);
                if ($admin != null) {
                    $verify = $this->passwordEncoder->isPasswordValid($admin, $body['password']);
                    if ($verify == true) {
                        $token = $generateToken->create($admin);
                        $admin->setapi_token($token); // attribute in the client entity
                        $entityManager->persist($admin);
                        $entityManager->flush();
                        return $this->response(json_encode(['message' => "login success",
                            'token' => $admin->getapi_token()]), Response::HTTP_OK);
                    } else {
                        return $this->response(json_encode(['error' => 'bad credentials'])
                            , Response::HTTP_BAD_REQUEST);
                    }

                } else {
                    return $this->response(json_encode(['error' => 'bad credentials'])
                        , Response::HTTP_BAD_REQUEST);

                }
            } else {
                return $this->response(json_encode(['error' => 'empty values not accepted'])
                    , Response::HTTP_BAD_REQUEST);

            }

        } catch (Exception $e) {
            echo ($e);
            $exception = array("error" => $e->getMessage());
            return $this->response(json_encode($exception), Response::HTTP_BAD_REQUEST);

        }
    }

}
