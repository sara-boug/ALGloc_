<?php 
  
  class  Client{   
     private $name ; 
     private $familyName; 
     private $email ; 
     private $password; 
     private $address ; 
     private $phoneNumber; 
     private $licenseNumber; 
    
     function __construct( string $name ,string $familyName , string  $email , string $password ,  string $address ,
      int  $phoneNumber , int $licenseNumber)
     {
          $this -> name = $name; 
          $this->familyName= $familyName; 
          $this -> address = $email; 
          $this -> address = $address; 
          $this -> phoneNumber= $phoneNumber; 
          $this -> licenseNumber = $licenseNumber; 

       
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

    public function getlicenseNumber(){ 
      return $this ->licenseNumber; 
    }
    public function setlicenseNumber(int $licenseNumber){ 
       $this -> licenseNumber = $licenseNumber; 
    }



  }
  


?> 