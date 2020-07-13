<?php 
 namespace App\Entity;
    use Symfony\Component\Validator\Constraints as  Assert; 
    use Doctrine\Common\Collections\Collection;
    use JMS\Serializer\Annotation as Serializer;
    use Hateoas\Configuration\Annotation as Hateoas; 
    use Doctrine\ORM\Mapping as ORM; 
    

      /**
      * @ORM\Entity(repositoryClass="App\Repository\AgencyRepository")
      * @Serializer\XmlRoot("agency")
      * @Hateoas\Relation("self" ,
      * href=@Hateoas\Route("get_agency" , parameters={"id"= "expr( object.getid())"}) 
      * )
      */
    class Agency { 
        /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         * @Serializer\XmlAttribute
         */
        private $id; 
        /** 
         * @ORM\Column(type="string" )
         * @Assert\NotBlank
          */
        private $agency_code; 
        /**
         *  @ORM\Column(type="string" , length=15 )
         * @Assert\NotBlank
        */
        private $phone_number; 
        /** 
         * @ORM\Column(type="string" , length=100 )
         * @Assert\NotBlank
         * @Assert\Email(message="invalid email")
         * */    
        private $email; 
        /** 
         * @ORM\Column(type="string", length=300 )
         * @Assert\NotBlank
        */
        private $address; 
        /**
          * @ORM\ManyToOne(targetEntity="App\Entity\City")
          * @ORM\JoinColumn(name="city" , referencedColumnName="id")
          */
        private  $city; 
          /**
            * @ORM\OneToMany(targetEntity="App\Entity\Vehicle" , mappedBy="agency")
          */
          /** @Serializer\Exclude */

        private $vehicles; 
        //getters and setters
      
        
        function getid() :int{ 
            return $this-> id; 
        }
        function setid(int $id) :void{ 
            $this ->id =$id; 
        }

        public  function getAgencyCode() :string{ 
            return $this->agency_code; 
        }
        public function setAgencyCode(string $agency_code) :void{ 
          $this->agency_code=$agency_code; 
        }

        public function getPhoneNumber() :string{ 

            return $this->phone_number; 
        }
        public function setPhoneNumber(string $phone_number) :void{ 
          $this ->phone_number=$phone_number; 
        }
        
        public function getEmail() :string{ 
            return $this->email; 
        }
        public function setEmail(string $email) :void{ 
          $this ->email=$email; 
        }

        public function getAddress() :string{ 
            return $this->address; 
        }
        public function setAddress(string $address) :void{ 
        $this ->address=$address; 
        }

        public function  getCity():City{ 
            return $this ->city; 
          }
          public function  setCity( City $city):void{ 
            $this ->city = $city; 
          }
        
          public function  getVehicles():Collection{ 
            return $this ->vehicles; 
          }
          public function  setvehicles( Collection $vehicles):void{ 
            $this ->vehicles= $vehicles; 
          }

      
    }

    