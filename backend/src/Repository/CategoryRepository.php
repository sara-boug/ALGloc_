<?php   
   namespace App\Repository;

    use App\Entity\Category;
    use App\Entity\Model;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface; 
    use Doctrine\Persistence\ManagerRegistry; 
  
    class CategoryRepository extends ServiceEntityRepository  { 
        private $modelRepo; 
        public function __construct(ManagerRegistry $registry , ModelRepository $modelRepo)
        {
            parent::__construct($registry, Category::class); 
            $this->modelRepo = $modelRepo; 
        }
        
        public function delete(int $id){ 
            $em=$this->getEntityManager(); 
            $models = $em->getRepository(Model::class) ->findBy(['category'=> $id]); 
            foreach($models as $model ) { 
                 $this->modelRepo->delete($model->getid());
            }
            $category= $em->getRepository(Category::class) ->findOneBy(['id' =>$id]); 
            $em->remove($category); 
            $em->flush(); 

        }

    }
    

?> 