<?php
  namespace App\security;

  use App\Entity\Admin_;
  use App\security\TokenAuthenticator ; 
  use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException; 
  use Symfony\Component\Security\Core\User\UserProviderInterface; 
  use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException; 
  class AdminAuth  extends TokenAuthenticator  {

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            if ($credentials === null) {return null;}
               
            $data = $this->jwtEncoder->decode($credentials);
             $user= $this->em->getRepository(Admin_::class)
                ->findOneBy(['api_token' => $credentials]);
             return $user;
        } catch (JWTDecodeFailureException $e) {
            throw new CustomUserMessageAuthenticationException("invvalid token");
        }
    }






  }




?> 