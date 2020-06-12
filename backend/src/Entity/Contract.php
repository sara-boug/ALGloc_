<?php

use Doctrine\Common\Collections\Collection;
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
                 * @ORM\ManyToOne(targetEntity="App\Entity\Client")
                 * @ORM\JoinColumn(name="client" , referencedColumnName="id")
                */
            private $client; 
                /**@ORM\ManyToOne(targetEntity="App\Entity\Vehicle")
                * @ORM\JoinColumn(name="vehicle" , referencedColumnName="id")
                */
            private $vehicle;           
                /**
                 * @ORM\OneToMany(targetEntity="App\Entity\Invoice" , mappedBy="contract")
                 */
            private $invoices; 

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

            function getagency() :int{ 
                return $this->agency; 
            }
            function setagency(int $agency) :void { 
                    $this->agency = $agency;
            }
         

            function getclient() :Client{ 
                return $this->client; 
            }
            function setclient(Client $client) :void { 
                    $this->client = $client;
            }
 
            function getvehicle() :Vehicle{ 
                return $this->vehicle; 
            }
            function setvehicle(Vehicle $vehicle) :void { 
                    $this->vehicle = $vehicle;
            }
    
            function getinvoices() :Collection { 
                return $this->invoices; 
            }
            function setinvoices(Collection $invoices) :void { 
                    $this->invoices = $invoices;
            }

            


    }

?> 