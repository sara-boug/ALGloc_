<?php

    use Doctrine\DBAL\Types\DateType;
    use Doctrine\ORM\Mapping as ORM; 
        /**
         * @ORM\Entity(repositoryClass="App\Repository\ContractRepository")
         * 
         */
        class Contract { 
            /**
             * @ORM\Id
             * @ORM\GeneratedValue
             * @ORM\Column(type="integer")
             */
            private $id;
                /** @ORM\Column(type="integer" , length=200)*/

            private $number;  // the contract Number
                /** @ORM\Column(type="time" )*/

            private $departure;
                /** @ORM\Column(type="time" )*/

            private $arrival; 
                /** @ORM\Column(type="time" , length=200)*/

            private $date;  // this date concerns the contract 
                /**
                 * @ORM\OneToOne(targetEntity="App\Entity\Client")
                 * @ORM\JoinColumn(name="id_Client" , referencedColumnName="id")
                */
            private $id_Client; 
                /**@ORM\OneToOne(targetEntity="App\Entity\Agency")
                * @ORM\JoinColumn(name="id_Agency" , referencedColumnName="id")
                */
                private $id_Agency; 
            function __construct(int $number,   DateType $departure, DateType  $arrival, DateType $date )
            {
                $this ->number=$number; 
                $this->departure=$departure; 
                $this->arrival=$arrival; 
                $this->date=$date; 
                
            }
            public function getid():int{ 
                return $this ->id; 
            }
            public function setid(int $id){ 
            $this->id = $id; 
            }
            
            public function getnumber():int{ 
                return $this->number; 
            }
            public function setnumber(int $number):void{ 
                $this->number=$number; 
            }

            public function getdeparture():DateTime{ 
                return $this ->departure; 
            }
            public function setdeparture(DateTime $departure){ 
            $this->departure= $departure; 
            }

            public function getarrival():DateTime{ 
                return $this ->arrival; 
            }
            public function setarrival(DateTime $arrival){ 
            $this->arrival= $arrival; 
            }

            public function getdate():DateTime{ 
                return $this ->date; 
            }
            public function setdate(DateTime $date){ 
            $this->date= $date; 
            }

            function getid_Agency() :int{ 
                return $this->id_Agency; 
            }
            function setid_Agency(int $id_Agency) :void { 
                    $this->id_Agency = $id_Agency;
            }

            function getid_Client() :int{ 
                return $this->id_Client; 
            }
            function setid_Client(int $id_Client) :void { 
                    $this->id_Client = $id_Client;
            }
    
    

    }

?> 