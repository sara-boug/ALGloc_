<?php   
   namespace App\Repository; 
    use App\Entity\Agency;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry; 

    class AgencyRepository extends ServiceEntityRepository  { 
    
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Agency::class); 
        }
        
        /** 
         * @return  Agency[]
         */
        public function findByCityId( int $id_city){ 
            $em=$this->getEntityManager(); 
            $query= $em->createQuery( '
               SELECT A 
               FROM App\Entity\Agency  A
               where A.city = :id_city          
            ')->setParameter( 'id_city' , $id_city); 
            return $query->getResult(); 
        } 
             
    }
    

?> 