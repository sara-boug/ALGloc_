<?php 
 namespace App\Entity;

use Doctrine\Common\Collections\Collection;
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
         *@ORM\ManyToOne(targetEntity="App\Entity\Wilaya")
         *@ORM\JoinColumn(name="wilaya" , referencedColumnName="id")
         */
        private $wilaya; 
         /**
          * @ORM\OneToMany(targetEntity="App\Entity\Client" , mappeBy="city")
          */
         private $clients;
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
         
        function getid_wilaya() :Wilaya
        {  
           return $this ->wilaya ; 
        }
        function setid_wilaya(Wilaya $wilaya) :void { 
             $this ->wilaya=$wilaya ; 
        }
        
        function getclients() :Collection{ 
           return $this->clients; 
        }
        function setclients(Collection $clients) :void{ 
            $this->clients = $clients; 
       }

   

 }


?> 