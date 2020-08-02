<?php   
   namespace App\Repository; 
    use App\Entity\Invoice;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry; 

    class InvoiceRepository extends ServiceEntityRepository  { 
    
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Invoice::class); 
        }
         // selecting an  invoice which contract belongs to a sepcific client 
        public function getClientInvoices(int  $clientId)  
        { 
            $em=$this->getEntityManager(); 
            $query= $em->createQuery('SELECT I FROM App\Entity\Invoice I 
                 WHERE  I.contract_ = (
                 SELECT C FROM App\Entity\Contract_ C WHERE C.client=:clientId )' )
                 ->setParameter('clientId', $clientId)
                 ; 
              return $query->getResult(); 
        }
  
    }
    

?> 