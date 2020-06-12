<?php
    namespace App\Entity; 
    use Doctrine\Common\Collections\Collection;
    use  Doctrine\ORM\Mapping as ORM ;

   /**
   * @ORM\Entity(repositoryClass="App\Repository\ModelRepository")
   */
 
   class Brand { 
            /** 
             * @ORM\Id; 
             * @ORM\GeneratedValue
             * @ORM\Column(type="integer")
            */

            private  $id; 
            /**
             * @ORM\Column(type="string" , length=200)
            */
            private   $name_; // model name_
            /**
              * @ORM\OneToMany(targetEntity="App\Entity\Model" , mappedBy="brand")
             */
            private $models; 
            function __construct(int $id , string  $name_)
            { 
                $this ->id =$id; 
                $this ->name_= $name_ ; 
                
            }
            function getid() :int 
            {  
            return $this ->id ; 
            }
            function setid(int $id) :void { 
                $this ->id =$id ; 
            }

            function getname_() :string
            {  
            return $this ->name_ ; 
            }
            function setname_(int $name_) :void { 
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

