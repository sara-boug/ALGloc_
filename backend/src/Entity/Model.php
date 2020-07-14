<?php
    namespace App\Entity; 
    use Doctrine\Common\Collections\Collection;
    use  Doctrine\ORM\Mapping as ORM ;
    use Hateoas\Configuration\Annotation as Hateoas;
    use JMS\Serializer\Annotation as Serializer;

   /**
   * @ORM\Entity(repositoryClass="App\Repository\ModelRepository")
   * @Serializer\XmlRoot("model")
   * @Hateoas\Relation("self" , 
   * href = @Hateoas\Route("get_model" , parameters={"id" ="expr(object.getid())"})
   * )
   */
 
   class Model { 
            /** 
             * @ORM\Id; 
             * @ORM\GeneratedValue
             * @ORM\Column(type="integer")
            */

            private  $id; 
            /**
             * @ORM\Column(type="string" , length=200)
            */
            private   $name_; // model name
            /**
              * @ORM\OneToMany(targetEntity="App\Entity\Vehicle" , mappedBy="model")
             */
              /** @Serializer\Exclude */

            private $vehicles; 
             /**
              *@ORM\ManyToOne(targetEntity="App\Entity\Category")
              *@ORM\JoinColumn(name="category" , referencedColumnName="id")
             */
            private $category; 
            /**
              *@ORM\ManyToOne(targetEntity="App\Entity\Brand")
              *@ORM\JoinColumn(name="brand" , referencedColumnName="id")
             */
            private $brand; 
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

            function getname() :string
            {  
            return $this ->name_ ; 
            }
            function setname(string $name_) :void { 
                $this ->name_=$name_ ; 
            }


            public function  getvehicles():Collection{ 
                return $this ->vehicles; 
              }
              public function  setvehicles( Collection $vehicles):void{ 
                $this ->vehicles= $vehicles; 
              }

              function getcategory() :Category
              {  
                return $this ->category ; 
              }
              function setcategory(Category $category) :void { 
                  $this ->category=$category; 
              }

              function getbrand() :Brand
              {  
                return $this ->brand ; 
              }
              function setbrand(Brand $brand) :void { 
                  $this ->brand=$brand; 
              }

  
        

   }
 
?> 