<?php 
  namespace App\Entity; 
  use Doctrine\ORM\Mapping as ORM;
   
  /**
   * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
   * 
   */
  class  Client{   
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
     private $id; 
     /** @ORM\Column(type="string" , length=200)*/
     private $name ; 
    /** @ORM\Column(type="string" , length=200)*/
     private $familyName; 
     /** @ORM\Column(type="string" , length=200)*/
     private $email ; 
    /** @ORM\Column(type="string" , length=200)*/
     private $password; 
   /** @ORM\Column(type="string" , length=300)*/
     private $address ; 
   /** @ORM\Column(type="integer" , length=200)*/
     private $phoneNumber; 
  /** @ORM\Column(type="integer" , length=200)*/
     private $licenseNumber; 

     /**
      * @ORM\ManyToOne(targetEntity="App\Entity\City")
      * @ORM\JoinColumn(name="city" , referencedColumnName="id")
      */
     private $city; 
     function __construct( string $name ,string $familyName , string  $email , string $password ,  string $address ,
      int  $phoneNumber , int $licenseNumber)
     {
          $this ->name = $name; 
          $this->familyName= $familyName; 
          $this ->address = $email; 
          $this ->address = $address; 
          $this ->phoneNumber= $phoneNumber; 
          $this ->licenseNumber = $licenseNumber; 

       
     }
     public function getid(){ 
        return $this ->id; 
     }
     public function setid(int $id){ 
       $this->id = $id; 
     }

     public function getname(){ 
       return $this -> name; 
      }
      public function setname(string $name){ 
        $this -> name = $name; 
      }

     public function getfamilyName(){ 
        return $this->familyName; 
     }
     public function setfamilyName( string $familyName){ 
       $this->familyName= $familyName; 
     }
    
     public function getaddress(){ 
       return $this ->address; 
     }
     public function setaddress(string $address){ 
        $this -> address = $address; 
     }

     public function getemail(){ 
      return $this ->email; 
    }
    public function setemail(string $email){ 
       $this -> address = $email; 
    }

    public function getpassword(){ 
      return $this ->password; 
    }
    public function setpassword(string $password){ 
       $this -> password = $password; 
    }


    public function getphoneNumber(){ 
      return $this ->phoneNumber; 
    }
    public function setphoneNumber(int $phoneNumber){ 
       $this -> phoneNumber= $phoneNumber; 
    }

    public function getlicenseNumber():int{ 
      return $this ->licenseNumber; 
    }
    public function setlicenseNumber(int $licenseNumber):void{ 
       $this -> licenseNumber = $licenseNumber; 
    }
     
    public function  getcity():City{ 
      return $this ->city; 
    }
    public function  setcity( City $city):void{ 
      $this ->city = $city; 
    }
   

  }
  


?> 