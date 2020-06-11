<?php 
 namespace App\Entity; 
 use  Doctrine\ORM\Mapping as ORM ; 
  /**
  * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
  */
 class City { 
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
        /**
         *@ORM\OneToOne(targetEntity="App\Entity\Wilaya")
         *@ORM\JoinColumn(name="id_wilaya" , referencedColumnName="id")
         */
        private $id_wilaya; 
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
         
        function getid_wilaya() :string
        {  
           return $this ->id_wilaya ; 
        }
        function setid_wilaya(int $id_wilaya) :void { 
             $this ->id_wilaya=$id_wilaya ; 
        }

   

 }


?> 