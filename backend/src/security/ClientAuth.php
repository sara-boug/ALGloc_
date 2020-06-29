<?php  
 namespace App\security;
  use App\security\TokenAuthenticator ; 
  use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException; 
  use Symfony\Component\Security\Core\User\UserProviderInterface; 
  use App\Entity\Client; 
  use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException; 
  class ClientAuth  extends TokenAuthenticator  {
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            if ($credentials === null) {return null;}               
             $data = $this->jwtEncoder->decode($credentials);
             $user= $this->em->getRepository(Client::class)
                ->findOneBy(['api_token' => $credentials]);
             return $user;
        } catch (JWTDecodeFailureException $e) {
            throw new CustomUserMessageAuthenticationException("invvalid token");
        }
    }
  }




?> 