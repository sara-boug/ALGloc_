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
 
                // selecting all except specific id
                public function selectExcept(int $id , int $vehicleId){ 
                    $em = $this->getEntityManager(); 
                    $query= $em->createQuery( '
                    SELECT C
                    FROM App\Entity\Contract_  C
                    where  ( C.id  != :id  and 
                      C.vehicle = :vehicleId  )
                   ')->setParameter( 'id' , $id )
                   ->setParameter('vehicleId' , $vehicleId) ; 
                    return $query->getResult(); 
        
        
                }
          
        
    }
    

?> 