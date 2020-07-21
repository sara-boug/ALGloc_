<?php   
   namespace App\Repository; 
     
     use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
     use Doctrine\Persistence\ManagerRegistry; 
     use  App\Entity\Admin_; 

    class Admin_Repository extends ServiceEntityRepository  { 
    
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Admin_::class); 
        }
           
        
    }
    

?> 