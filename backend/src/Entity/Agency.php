<?php 
 namespace App\Entity; 
 use Doctrine\ORM\Mapping as ORM; 

 /**
  * @ORM\repositoryEntity(repositoryClass="App\Repository\AgencyRepository")
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
      * @ORM\OneToOne(targetEntity="App\Entity\City")
      * @ORM\JoinColumn(name="id_City" , referencedColumnName="id")
      */
    private  $id_City; 
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

    public function  getid_City():int{ 
        return $this ->id_City; 
      }
      public function  serid_City( int $id_City):void{ 
        $this ->id_City = $id_City; 
      }
     
  
 }

?> 