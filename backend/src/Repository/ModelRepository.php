<?php   
   namespace App\Repository; 
    use App\Entity\Model;
    use App\Entity\Vehicle;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface; 
    use Doctrine\Persistence\ManagerRegistry; 
       
    class ModelRepository extends ServiceEntityRepository  { 
        private   $vehicleRepo; 
        public function __construct(ManagerRegistry $registry , VehicleRepository $vehicleRepo)
        {
            parent::__construct($registry, Model::class); 
            $this->vehicleRepo = $vehicleRepo ; 
        }


        public function delete(int $id){ 
            $em=$this->getEntityManager(); 
            $vehicles = $em->getRepository(Vehicle::class) ->findBy(['model'=> $id]); 
            foreach($vehicles as $vehicle) { 
                $this->vehicleRepo($vehicle->getid()); 
            }
            $model= $em->getRepository(Model::class) ->findOneBy(['id' =>$id]); 
            $em->remove($model); 
            $em->flush(); 

        }

         
    }
    

?> 