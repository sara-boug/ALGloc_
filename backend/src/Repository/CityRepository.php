<?php   
   namespace App\Repository;

use App\Entity\Agency;
use App\Entity\City;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface; 
    use Doctrine\Persistence\ManagerRegistry; 

    class CityRepository extends ServiceEntityRepository  { 
       private $clientRepo; 
       private $agencyRepo; 
        public function __construct(ManagerRegistry $registry , AgencyRepository $agencyRepo , ClientRepository $clientRepo)
        {
            parent::__construct($registry, City::class); 
            $this->agencyRepo = $agencyRepo; 
            $this->clientRepo= $clientRepo; 
        }
    
        



        public function delete(int $id){ 
            $em=$this->getEntityManager(); 
            $agencies= $em->getRepository(Agency::class) ->findBy(['city'=> $id]); 
            $clients= $em->getRepository(Client::class) ->findBy(['city'=> $id]); 

            foreach( $agencies as $agency ) { 
               $this->agencyRepo->delete($agency->getid()); 
            }
            
            foreach( $clients as $client ) { 
                $this->clientRepo->delete($client->getid());
             }
            $brand= $em->getRepository(City::class) ->findOneBy(['id' =>$id]); 
            $em->remove($brand); 
            $em->flush(); 

        }
          

    }
    

?> 