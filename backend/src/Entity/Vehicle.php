<?php
    namespace App\Entity;

    use Doctrine\DBAL\Types\BinaryType;
    use Doctrine\DBAL\Types\BlobType;
    use Doctrine\ORM\Mapping as ORM; 
    /** @ORM\Entity(repositoryClass="App\Entity\VehicleRepository") */
    class Vehicle { 
        /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         */
    private $id; 
            /** @ORM\Column(type="string" )*/
    private $registration_number; 
            /** @ORM\Column(type="float" )*/
    private $rental_price; 
            /** @ORM\Column(type="float" )*/
    private $inssurance_price; 
            /** @ORM\Column(type="float" )*/
    private  $deposit; 
            /** @ORM\Column(type="integer" )*/
    private $passenger_number; 
            /** @ORM\Column(type="blob" )*/
    private $image_; 
            /** @ORM\Column(type="integer" )*/
    private $suitcase_number; 
            /** @ORM\Column(type="string"  , length=200)*/
    private $state_; 
            /** @ORM\Column(type="string" , length=100)*/
    private $gearbox; 
            /** @ORM\Column(type="string" , length=300)*/
    private $status_; 
           /**@ORM\ManyToOne(targetEntity="App\Entity\Agency")
            * @ORM\JoinColumn(name="agency" , referencedColumnName="id")
            */
    private $agency;   
            /**
            * @ORM\ManyToOne(targetEntity="App\Entity\Model")
            * @ORM\JoinColumn(name="model" , referencedColumnName="id")
            */
    private $model; 

    function __construct( string $registration_number, float $rental_price, float $inssurance_price,
    int $passenger_number , BlobType $image_ , string $suitcase_number , string   $state_,  string $gearbox , string $status_ )
    {
        $this->registrationNummer = $registration_number;
        $this->rental_price =$rental_price; 
        $this->inssurance_price=$inssurance_price; 
        $this->passenger_number=$passenger_number; 
        $this->image_=$image_; 
        $this->suitcase_number=$suitcase_number; 
        $this->state_=$state_; 
        $this->$gearbox=$gearbox; 
        $this->$status_ =$status_; 

    }

        function getid():int{ 
            return $this ->id ; 
        }
        function setid( int $id ) :void { 
            $this ->id=$id; 
        }

        function getregistration_number():string{ 
            return $this->registration_number; 
        }
        function setregistration_number( string $registration_number) :void { 
            $this ->registration_number = $registration_number; 
        }

        
        function getrental_price():float{ 
            return $this ->rental_price; 
        }
        function setrental_price(  float $rental_price ) :void { 
            $this ->rental_price=$rental_price; 
        }
    
        function getinssurance_price():float{ 
            return $this ->inssurance_price; 
        }
        function setinssurance_price( float $inssurance_price ) :void { 
            $this ->inssurance_price=$inssurance_price; 
        }

        function getdeposit():float{ 
            return $this ->deposit; 
        }
        function setideposit( float $deposit ) :void { 
            $this ->deposit=$deposit; 
        }

        function getpassenger_number():int{ 
            return $this ->passenger_number; 
        }
        function setpassenger_number( int $passenger_number ) :void { 
            $this ->passenger_number=$passenger_number; 
        }

        function getimage_():BlobType{ 
            return $this ->image_; 
        }
        function setimage_( BinaryType $image_ ) :void { 
            $this ->image_=$image_; 
        }

        function getsuitcase_number():int { 
            return $this ->suitcase_number; 
        }
        function setsuitcase_number( int $suitcase_number ) :void { 
            $this ->suitcase_number=$suitcase_number; 
        }

        function getstate_():string{ 
            return $this ->state_; 
        }
        function setstate_( string $state_ ) :void { 
            $this ->state_=$state_; 
        }


        function getgearbox():string{ 
            return $this ->gearbox; 
        }
        function setgearbox( string $gearbox) :void { 
            $this ->gearbox=$gearbox; 
        }

        function getstatus_():string{ 
            return $this ->status_; 
        }
        function setstatus_( string $status_) :void { 
            $this ->status_=$status_; 
        }
        
        function getagency() : Agency{ 
           return $this->agency; 
        }
        function setagency(Agency $agency) :void { 
               $this->agency = $agency;
        }


        function getmodel() : Model{ 
            return $this->model; 
         }
         function setmodel(Model $model) :void { 
                $this->model = $model;
         }
 
    }

    

?>