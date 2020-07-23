<?php   
   namespace App\Repository; 
    use App\Entity\Agency;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry; 

    class AgencyRepository extends ServiceEntityRepository  { 
        private $vehicleRepo ; 
    
        public function __construct(ManagerRegistry $registry  , VehicleRepository $vehicleRepo)
        {
            parent::__construct($registry, Agency::class); 
            $this->vehicleRepo = $vehicleRepo; 
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
        
        public function delete(int $id ){ 
            $em = $this->getEntityManager(); 
            $vehicles = $em->getRepository(Vehicle::class)-> findBy(['agency' => $id]);
            foreach($vehicles as $vehicle) { 
                $this->vehicleRepo->delete($vehicle->getid()); 
            }
            $agency = $em->getRepository(Agency::class)->findOneBy(['id' =>$id]);
            $em->remove($agency); 
             $em->flush(); 

        }
             
    }
    

?> 