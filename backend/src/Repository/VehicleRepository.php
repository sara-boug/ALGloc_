<?php   
   namespace App\Repository;

use App\Entity\Contract_;
use App\Entity\Vehicle;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry; 

    class VehicleRepository extends ServiceEntityRepository  { 
        private   $contractRepo ; 
        public function __construct(ManagerRegistry $registry ,Contract_Repository $contractRepo)
        {
            parent::__construct($registry, Vehicle::class); 
            $this->contractRepo = $contractRepo; 
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


        public function delete(int $id) 
        { 
            $em = $this->getEntityManager(); 
            $contracts=$em->getRepository(Contract_::class) -> findBy(['vehicle' => $id]);
            foreach($contracts as $contract) { 
             $this->contractRepo->delete($contract->getid()); 
            
            }
            $vehicle= $em->getRepository(Vehicle::class) ->findOneBy(['id'=>$id]); 
            $em->remove($vehicle); 
            $em->flush(); 
           
          
        }
    }
    

?> 