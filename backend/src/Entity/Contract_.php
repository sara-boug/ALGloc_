<?php
    namespace App\Entity; 
    use Doctrine\Common\Collections\Collection;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM; 
        /**
         * @ORM\Entity(repositoryClass="App\Repository\Contract_Repository")
         * 
         */
        class Contract_ { 
            /**
             * @ORM\Id
             * @ORM\GeneratedValue
             * @ORM\Column(type="integer")
             */
            private $id;
                /** @ORM\Column(type="integer" , length=200)*/

            private $number_;  // the contract Number_
                /** @ORM\Column(type="time" )*/

            private $departure;
                /** @ORM\Column(type="time" )*/

            private $arrival; 
                /** @ORM\Column(type="time" )*/

            private $date_;  // this date concerns the contract 
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
                /**
                 * @ORM\ManyToOne(targetEntity="App\Entity\Agency")
                * @ORM\JoinColumn(name="arrival_agency" , referencedColumnName="id")
                */
            private $arrival_agency; 

            function __construct(int $number_,   DateTime $departure, DateTime  $arrival, DateTime $date_ )
            {
                $this ->number_=$number_; 
                $this->departure=$departure; 
                $this->arrival=$arrival; 
                $this->date_=$date_; 
                
            }
            public function getid():int{ 
                return $this ->id; 
            }
            public function setid(int $id){ 
            $this->id = $id; 
            }
            
            public function getnumber_():int{ 
                return $this->number_; 
            }
            public function setnumber_(int $number_):void{ 
                $this->number_=$number_; 
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

            public function getdate_():DateTime{ 
                return $this ->date_; 
            }
            public function setdate_(DateTime $date_){ 
            $this->date_= $date_; 
            }

            function getagency() :Agency{ 
                return $this->agency; 
            }
            function setagency(Agency $agency) :void { 
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

            function getarrival_agency() :Agency { 
                return $this->arrival_agency; 
            }
            function setarrival_agency(Agency $arrival_agency) :void { 
                    $this->arrival_agency = $arrival_agency;
            }

            


    }
