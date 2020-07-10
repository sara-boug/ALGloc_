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
        private $name_;
        /**
         *@ORM\ManyToOne(targetEntity="App\Entity\Wilaya")
         *@ORM\JoinColumn(name="wilaya" , referencedColumnName="id")
         */
        private $wilaya; 
         /**
          * @ORM\OneToMany(targetEntity="App\Entity\Client" , mappedBy="city")
          */
         private $clients;
          /**
          * @ORM\OneToMany(targetEntity="App\Entity\Agency" , mappedBy="city")
          */
         private $agencies; 
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

        function getName() :string
        {  
 
           return $this ->name_ ; 
        }
        function setName(string $name_) :void { 
             $this ->name_=strtolower(trim($name_) ); 
        }
         
        function getWilaya() :Wilaya
        {  
           return $this ->wilaya ; 
        }
        function setWilaya(Wilaya $wilaya) :void { 
             $this ->wilaya=$wilaya ; 
        }
        
        function getClients() :Collection{ 
           return $this->clients; 
        }
        function setClients(Collection $clients) :void{ 
            $this->clients = $clients; 
       }

       function getAgencies() :Collection{ 
          return $this->agencies; 
       }
       function setAgencies(Collection $agencies) :void{ 
           $this->agencies = $agencies; 
      }


   

 }


?> 