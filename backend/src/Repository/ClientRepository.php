<?php   
   namespace App\Repository; 
    use App\Entity\Client;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface; 
    use Doctrine\Persistence\ManagerRegistry; 

    class ClientRepository extends ServiceEntityRepository  { 
    
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Client::class); 
        }
      /*  public function loadUserByUsername($email)
        {
            return $this ->createQuery('SELECT U FROM App\Entity\Client U WHERE U.email =:params')  
             ->setParameter('params' , $email)
             ->getQuery() -> getOneOrNullResult(); 
        }
        */
        public function deleteAll() { 
            $entityManager = $this ->getEntityManager();   
            return  $entityManager ->createQuery(
                'Delete from App\Entity\Client'
            ) ->getResult() ; 
        }

        
    }
    

?> 