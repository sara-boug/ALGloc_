<?php

use Doctrine\Common\Collections\Collection;
use  Doctrine\ORM\Mapping as ORM ;

   /**
   * @ORM\Entity(repositoryClass="App\Repository\ModelRepository")
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
            private   $name; // model name
            /**
              * @ORM\OneToMany(targetEntity="App\Entity\Vehicle" , mappedBy="model")
             */
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
            function __construct(int $id , string  $name)
            { 
                $this ->id =$id; 
                $this ->name= $name ; 
                
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
            return $this ->name ; 
            }
            function setname(int $name) :void { 
                $this ->name=$name ; 
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