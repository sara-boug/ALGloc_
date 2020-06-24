<?php   
   namespace App\Repository; 
    use App\Entity\Wilaya;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface; 
    use Doctrine\Persistence\ManagerRegistry; 

    class WilayaRepository extends ServiceEntityRepository  { 
    
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Wilaya::class); 
        }
 
        
    }
    

?> 