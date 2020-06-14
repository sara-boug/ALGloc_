<?php   
   namespace App\Repository; 
    use App\Entity\Client;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry; 

    class ClientRepository extends ServiceEntityRepository{ 
    
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Client::class); 
        }
        
        public function deleteAll() { 
            $entityManager = $this ->getEntityManager();   
            return  $entityManager ->createQuery(
                'Delete from App\Entity\Client'
            ) ->getResult() ; 
        }

        
    }
    

?> 