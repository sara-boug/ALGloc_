<?php   
   namespace App\Repository; 
    use App\Entity\Client;
     use App\Entity\Contract_;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface; 
    use Doctrine\Persistence\ManagerRegistry; 
    use App\Repository\Contract_Repository; 

    class ClientRepository extends ServiceEntityRepository  { 
         private $contractRepo  ; 
        public function __construct(ManagerRegistry $registry, Contract_Repository $contractRepo )
        {
            parent::__construct($registry, Client::class); 
            $this->contractRepo =$contractRepo; 
        }
         public function deleteAll() { 
            $entityManager = $this ->getEntityManager();   
            return  $entityManager ->createQuery(
                'Delete from App\Entity\Client'
            ) ->getResult() ; 
        }
 
        public  function delete(int $id)
        {
            // deleting a client require deleting the contracts related to the clients
             $em= $this->getEntityManager(); 
             $contracts =$em->getRepository(Contract_::class)->findBy(['client'=>$id]); 
               foreach($contracts as   $contract) { 
                   $this->contractRepo ->delete($contract->getid()); 
             }
             $client = $em->getRepository(Client::class)->findOneBy(['id' =>$id]);
             $em->remove($client); 
             $em->flush();
        }
 
        
    }
    

?> 