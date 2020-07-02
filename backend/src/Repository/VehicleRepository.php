<?php   
   namespace App\Repository; 
    use App\Entity\Vehicle;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry; 

    class VehicleRepository extends ServiceEntityRepository  { 
    
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Vehicle::class); 
        }
        /** 
         * @return  Vehicle[]
         */
        public function findByAgencyId( int $id_agency){ 
            $em=$this->getEntityManager(); 
            $query= $em->createQuery( '
               SELECT v 
               FROM App\Entity\Vehicle  v 
               where v.agency = :id_agency          
            ')->setParameter('id_agency' , $id_agency); 
            return $query->getResult(); 
        }
    }
    

?> 