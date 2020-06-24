<?php   
   namespace App\Repository; 
    use App\Entity\Contract_;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry; 

    class Contract_Repository extends ServiceEntityRepository  { 
    
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Contract_::class); 
        }
 
        
    }
    

?> 