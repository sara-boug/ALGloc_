<?php
namespace App\security;

  use App\Entity\Client;
  use Symfony\Component\Security\Guard\AbstractGuardAuthenticator; 
  use Doctrine\ORM\EntityManagerInterface;
  use  Symfony\Component\HttpFoundation\JsonResponse; 
  use  Symfony\Component\HttpFoundation\Request; 
  use  Symfony\Component\HttpFoundation\Response; 
  use Symfony\Component\Security\Core\User\UserProviderInterface; 
  use Symfony\Component\Security\Core\User\UserInterface; 
  use Symfony\Component\Security\Core\Exception\AuthenticationException; 
  use \Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
class TokenAuthenticator extends AbstractGuardAuthenticator { 
     
     private $em; 
     public function __construct(EntityManagerInterface $em)
     {
         $this->em = $em; 
     }

     public function supports(Request $request){ 
         
         return $request ->headers ->has('X-AUTH-TOKEN'); 
     }
      
     public function getCredentials( Request $request)
     {
        return $request ->headers->get('X-AUTH-TOKEN'); 

     }
     public function getUser($credentials, UserProviderInterface $userProvider)
     {
         if($credentials ===null) { 
             return null ; 
         }

         return $this->em ->getRepository(Client::class)
          ->findOneBy(['api_token' =>$credentials]); 
     }

     public function checkCredentials($credentials, UserInterface  $client)
     {
         return true; 
         
     } 
     public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
     {
         return new Response(json_encode( ['message' => 'login success']), Response::HTTP_OK); 
     }
      public function onAuthenticationFailure( Request $request, AuthenticationException $exception)
     {
         $data = [
             'message' => strtr($exception->getMessageKey , $exception ->getMessageData)
         ]; 
         return new Response(json_encode( $data ), Response::HTTP_UNAUTHORIZED); 
     }
     

     public function start(Request $request, AuthenticationException $authException = null)
     {
         $data = [ 
             'message' => ' Authentication required'
         ] ;
         return new Response(json_encode($data) , Response::HTTP_UNAUTHORIZED); 
     }

     public function supportsRememberMe()
     {
          return false; 
     }

    
  }



?>