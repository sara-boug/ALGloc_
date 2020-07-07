<?php
    namespace App\Entity;
    use JMS\Serializer\Annotation as Serializer;
    use Hateoas\Configuration\Annotation as Hateoas; 
    use Doctrine\DBAL\Types\BinaryType;
    use Doctrine\DBAL\Types\BlobType;
    use Doctrine\ORM\Mapping as ORM; 
    /** @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
     *  @Serializer\XmLRoot("vehicle")
     *  @Hateoas\Relation("self" , href="exp('/admin/vehicle/'~ object.getid())")
     */
    class Vehicle { 
        /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         * @Serializer\XmlAttribute
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
           /*
            *@ORM\ManyToOne(targetEntity="App\Entity\Agency")
            * @ORM\JoinColumn(name="agency" , referencedColumnName="id")
             
      private $agency;   
          
            * @ORM\ManyToOne(targetEntity="App\Entity\Model")
            * @ORM\JoinColumn(name="model" , referencedColumnName="id")
            */
    //private $model; */

        function getid():int{ 
            return $this ->id ; 
        }
        function setid( int $id ) :void { 
            $this ->id=$id; 
        }

        function getRegistrationNumber():string{ 
            return $this->registration_number; 
        }
        function setRegistrationNumber( string $registration_number) :void { 
            $this ->registration_number = $registration_number; 
        }

        
        function getRentalPrice():float{ 
            return $this ->rental_price; 
        }
        function setRentalPrice(  float $rental_price ) :void { 
            $this ->rental_price=$rental_price; 
        }
    
        function getInssurancePrice():float{ 
            return $this ->inssurance_price; 
        }
        function setInssurancePrice( float $inssurance_price ) :void { 
            $this ->inssurance_price=$inssurance_price; 
        }

        function getDeposit():float{ 
            return $this ->deposit; 
        }
        function setDeposit( float $deposit ) :void { 
            $this ->deposit=$deposit; 
        }

        function getPassengerNumber():int{ 
            return $this ->passenger_number; 
        }
        function setPassengerNumber( int $passenger_number ) :void { 
            $this ->passenger_number=$passenger_number; 
        }

        function getImage() { 
            return $this ->image_; 
        }
        function setImage(  $image_ ) :void { 
            $this ->image_=$image_; 
        }

        function getSuitcaseNumber():int { 
            return $this ->suitcase_number; 
        }
        function setSuitcaseNumber( int $suitcase_number ) :void { 
            $this ->suitcase_number=$suitcase_number; 
        }

        function getState():string{ 
            return $this ->state_; 
        }
        function setState( string $state_ ) :void { 
            $this ->state_=$state_; 
        }


        function getGearbox():string{ 
            return $this ->gearbox; 
        }
        function setGearbox( string $gearbox) :void { 
            $this ->gearbox=$gearbox; 
        }

        function getStatus():string{ 
            return $this ->status_; 
        }
        function setStatus( string $status_) :void { 
            $this ->status_=$status_; 
        }
      /*  
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
 */
    }

    

?>