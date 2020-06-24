<?php 
namespace tests\a_integerationTest;
    use App\Entity\Admin_;
    use App\Entity\Agency;
    use App\Entity\Category;
    use App\Entity\Model;
    use App\Entity\Vehicle;
    use App\Entity\Wilaya;
    use App\Entity\Brand; 
    use App\Entity\Invoice; 
    use App\Entity\City; 
    use App\Entity\Client; 
    use App\Entity\Contract_; 
    use Doctrine\ORM\EntityManager;
    use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
 
class dbTest  extends KernelTestCase{ 
         
         public function testShowPost(){ 
            self::bootKernel(); 
            $entities=  [Admin_::class , Agency::class , Brand::class , Category::class , City::class , 
            Client::class , Contract_::class ,Invoice::class , Model::class , 
            Vehicle::class , Wilaya::class ];
            $this->truncateEntities( $entities ); 
 
  
        } 
        //clearing the database tables before the tastes 
        private function  truncateEntities (array $entities ){ 
            $connection= $this->getEntityManager() ->getConnection(); 
            $databasePlateform = $connection ->getDatabasePlatform(); 
            if($databasePlateform ->supportsForeignKeyConstraints()) { 
                $connection->query('set FOREIGN_KEY_CHECKS=0'); 
            }
            foreach( $entities as $entity){ 
                $query = $databasePlateform ->getTruncateTableSQL(
                    $this->getEntityManager() ->getClassMetadata($entity)->getTableName()
                ); 
          
                $connection->executeUpdate($query); 
                $count=  $this->getEntityManager()->getRepository($entity) ->findAll(); 
 
                 $this->assertEquals(sizeof($count) , 0); 


            
            }
            if($databasePlateform ->supportsForeignKeyConstraints()) { 
                $connection->query('set FOREIGN_KEY_CHECKS=1'); 
            }

        }
        private function getEntityManager() : EntityManager{ 
            return self::$kernel-> getContainer()
                        -> get('doctrine')
                        ->getManager(); 
        }
    }
   
 
?> 