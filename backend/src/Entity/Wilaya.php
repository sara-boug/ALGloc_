<?php 
 namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use  Doctrine\ORM\Mapping as ORM ;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

 /**
  * @ORM\Entity(repositoryClass="App\Repository\WilayaRepository")
  *  @Serializer\XmlRoot("vehicle")
  */
 class Wilaya { 
        /** 
         * @ORM\Id; 
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
        */
        private $id ; 
        /**
         * @ORM\Column(type="string" , length=200)
         */
        private $name_; 
        /**
         * @ORM\OneToMany(targetEntity="App\Entity\City" , mappedBy="wilaya")
         */
        /** @Serializer\Exclude */

        private $cities; 
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

        function getname() :string
        {  
           return $this ->name_ ; 
        }
        function setname(int $name_) :void { 
             $this ->name_=strtolower( trim($name_)) ; 
        }

        function getcities() :Collection
        {  
           return $this ->cities ; 
        }
        function setcities(Collection $cities) :void { 
             $this ->cities =$cities ; 
        }

   

 }


?> 