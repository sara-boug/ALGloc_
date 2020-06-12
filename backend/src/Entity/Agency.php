<?php 
 namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM; 

 /**
  * @ORM\Entity(repositoryClass="App\Repository\AgencyRepository")
  */
 class Agency { 
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id; 
    /** @ORM\Column(type="string" )*/
    private $agency_code; 
    /** @ORM\Column(type="string" , length=15 )*/
    private $phone_number; 
    /** @ORM\Column(type="string" , length=100 )*/    
    private $email; 
    /** @ORM\Column(type="string", length=300 )*/
    private $address; 
    /**
      * @ORM\ManyToOne(targetEntity="App\Entity\City")
      * @ORM\JoinColumn(name="city" , referencedColumnName="id")
      */
    private  $city; 
       /**
        * @ORM\OneToMany(targetEntity="App\Entity\Vehicle" , mappedBy="agency")
       */
    private $vehicles; 
      function __construct(string $agency_code , int $phone_number , string $email, string $address )
     {
         $this ->agency_code = $agency_code; 
         $this-> $phone_number=$phone_number; 
         $this ->email=$email; 
         $this->address = $address; 
     }

    function __construct2() {    /*empty construtor*/ }
    
    //getters and setters
    function getid() :int{ 
        return $this-> id; 
    }
    function setid(int $id) :void{ 
        $this ->id =$id; 
    }

    function getagency_code() :string{ 
        return $this->agency_code; 
    }
    function setagency_code(string $agency_code) :void{ 
    $this ->agency_code=$agency_code; 
    }

    function getphone_number() :string{ 
        return $this->phone_number; 
    }
    function setphone_number(string $phone_number) :void{ 
      $this ->phone_number=$phone_number; 
    }
    
    function getemail() :string{ 
        return $this->email; 
    }
    function setemail(string $email) :void{ 
    $this ->email=$email; 
    }

    function getaddress() :string{ 
        return $this->address; 
    }
    function setaddress(string $address) :void{ 
    $this ->address=$address; 
    }

    public function  getcity():City{ 
        return $this ->city; 
      }
      public function  setcity( City $city):void{ 
        $this ->city = $city; 
      }
     
      public function  getvehicles():Collection{ 
        return $this ->vehicles; 
      }
      public function  setvehicles( Collection $vehicles):void{ 
        $this ->vehicles= $vehicles; 
      }

   
 }

?> 