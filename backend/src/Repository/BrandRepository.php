<?php   
   namespace App\Repository;

    use App\Entity\Brand;
    use App\Entity\Model;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface; 
    use Doctrine\Persistence\ManagerRegistry; 

    class BrandRepository extends ServiceEntityRepository  { 
        private  $modelRepo; 
        public function __construct(ManagerRegistry $registry , ModelRepository $modelRepo)
        {
            parent::__construct($registry, Brand::class); 
            $this->modelRepo=$modelRepo; 
        }


        public function delete(int $id){ 
            $em=$this->getEntityManager(); 
            $models = $em->getRepository(Model::class) ->findBy(['brand'=> $id]); 
            foreach($models as $model ) { 
                $this->modelRepo->delete($model->getid());

            }
            $brand= $em->getRepository(Brand::class) ->findOneBy(['id' =>$id]); 
            $em->remove($brand); 
            $em->flush(); 

        }
          
    }
    

?> 