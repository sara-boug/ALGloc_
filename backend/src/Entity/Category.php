<?php
      namespace App\Entity; 

  use Doctrine\Common\Collections\Collection;
  use  Doctrine\ORM\Mapping as ORM ;
  use Hateoas\Configuration\Annotation as Hateoas;
  use JMS\Serializer\Annotation as Serializer;

   /**
   * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
   * @Serializer\XmlRoot("category")
   * @Hateoas\Relation("self" , href=
   * @Hateoas\Route("get_category" , parameters ={ "id" = "expr(object.getid())" } )
   * )
   */
 
   class Category { 
            /** 
             * @ORM\Id; 
             * @ORM\GeneratedValue
             * @ORM\Column(type="integer")
             * @Serializer\XmlAttribute
              */
            private  $id; 
            /**
             * @ORM\Column(type="string" , length=200)
            */
            private   $name_; // category name
              /**
              * @ORM\OneToMany(targetEntity="App\Entity\Model" , mappedBy="category")
             */
             /** @Serializer\Exclude */

            private $models; 
            function __construct()
            { 

            }
            function getid() :int 
            {  
            return $this ->id ; 
            }
            function setid(int $id) :void { 
                $this ->id =$id ; 
            }

            function getName() :string
            {  
            return $this ->name_ ; 
            }
            function setName(string $name_) :void { 
                $this ->name_=$name_ ; 
            }

            public function  getmodels():Collection{ 
                return $this ->models; 
              }
              public function  setmodels( Collection $models):void{ 
                $this ->models= $models; 
              }
        

   }
 
?> 

