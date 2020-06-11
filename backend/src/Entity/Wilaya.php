<?php 
 namespace App\Entity; 
 use  Doctrine\ORM\Mapping as ORM ; 
 /**
  * @ORM\Entity(repositoryClass="App\Repository\WilayaRepository")
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
        private $name; 
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

   

 }


?> 