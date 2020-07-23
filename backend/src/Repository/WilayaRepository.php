<?php   
   namespace App\Repository;

use App\Entity\City;
use App\Entity\Wilaya;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface; 
    use Doctrine\Persistence\ManagerRegistry; 

    class WilayaRepository extends ServiceEntityRepository  { 
      private $cityRepo; 
        public function __construct(ManagerRegistry $registry ,CityRepository $cityRepo)
        {
            parent::__construct($registry, Wilaya::class); 
            $this->cityRepo=$cityRepo; 
        }
 

        public function delete(int $id){ 
            $em=$this->getEntityManager(); 
            $cities = $em->getRepository(City::class) ->findBy(['wilaya'=> $id]); 
            foreach($cities as $city) { 
               $this->cityRepo->delete($city->getid()); 
            }
            $brand= $em->getRepository(Wilaya::class) ->findOneBy(['id' =>$id]); 
            $em->remove($brand); 
            $em->flush(); 

        }
          

        
    }
    

?> 