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
    private $agencyCode; 
    /** @ORM\Column(type="integer" , length=15 )*/
    private $phoneNumber; 
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
      function __construct(string $agencyCode , int $phoneNumber , string $email, string $address )
     {
         $this ->agencyCode = $agencyCode; 
         $this-> $phoneNumber=$phoneNumber; 
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

    function getagencyCode() :string{ 
        return $this->agencyCode; 
    }
    function setagencyCode(string $agencyCode) :void{ 
    $this ->agencyCode=$agencyCode; 
    }

    function getphoneNumber() :int{ 
        return $this->phoneNumber; 
    }
    function setphoneNumber(string $agencyCode) :void{ 
    $this ->agencyCode=$agencyCode; 
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