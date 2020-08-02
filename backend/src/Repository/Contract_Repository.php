<?php
    namespace App\Repository;

        use App\Entity\Contract_;
        use App\Entity\Invoice;
        use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
        use Doctrine\Persistence\ManagerRegistry;

        class Contract_Repository extends ServiceEntityRepository
        {

            public function __construct(ManagerRegistry $registry)
            {
                parent::__construct($registry, Contract_::class);
            }

            // selecting all except specific id
            public function selectExcept(int $id, int $vehicleId)
            {
                $em = $this->getEntityManager();
                $query = $em->createQuery('
                            SELECT C
                            FROM App\Entity\Contract_  C
                            where  ( C.id  != :id  and
                            C.vehicle = :vehicleId  )
                        ')->setParameter('id', $id)
                    ->setParameter('vehicleId', $vehicleId);
                return $query->getResult();

            }

            public function delete(int $id) 
            { 
                //deleting the invoices  related to the contracts 
                $em= $this->getEntityManager(); 
                $invoices= $em->getRepository(Invoice::class)->findBy(['contract_' =>$id]);
                foreach($invoices as $invoice){ 
                  $em->remove($invoice); 
                  $em->flush();
                }
                //  deleting  the contract 
                $contract = $em->getRepository(Contract_::class)->find( $id);
                $em->remove($contract); 
                $em->flush();
         
            }

            public function selectContractByIdClientId(int $id , $clientId){ 
               $em = $this->getEntityManager(); 
               $query=  $em->createQuery(
                   'SELECT C FROM App\Entity\Contract_ C 
                     where ( C.id =:id  and C.client=: client )'
               ) ->setParameter( 'id' , $id)
                 ->setParameter('client' , $clientId); 
               return $query ->getResult();

            }


            

        }
