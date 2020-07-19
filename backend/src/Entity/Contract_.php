<?php
    namespace App\Entity;

        use DateTime;
        use Doctrine\Common\Collections\Collection;
        use Doctrine\ORM\Mapping as ORM;

        /**
         * @ORM\Entity(repositoryClass="App\Repository\Contract_Repository")
         *
         */
        class Contract_
        {
            /**
             * @ORM\Id
             * @ORM\GeneratedValue
             * @ORM\Column(type="integer")
             */
            private $id;
            /** @ORM\Column(type="integer" , length=200)*/

            private $date_; // this date concerns the contract

            /** @ORM\Column(type="time" )*/
            private $arrival;
            /** @ORM\Column(type="time" )*/
            private $departure;

            /**
             * @ORM\ManyToOne(targetEntity="App\Entity\Client")
             * @ORM\JoinColumn(name="client" , referencedColumnName="id" , nullable=false)
             */
            private $client;
            /**
             * @ORM\OneToMany(targetEntity="App\Entity\Invoice" , mappedBy="contract")
             */

            private $invoices;
            /**
             * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle")
             * @ORM\JoinColumn(name="vehicle"  , referencedColumnName="id" , nullable=false)
             */

            private $vehicle;

            public function __construct()
            {
            }
            public function getid(): int
            {
                return $this->id;
            }
            public function setid(int $id)
            {
                $this->id = $id;
            }

            public function getDate() 
            {
                return $this->date_;
            }
            public function setDate(  $date_)
            {
                $this->date_ = $date_;
            }

            public function getclient(): Client
            {
                return $this->client;
            }
            public function setclient(Client $client): void
            {
                $this->client = $client;
            }


            public function getVehicle(): Vehicle
            {
                return $this->vehicle;
            }
            public function setVehicle(Vehicle $vehicle)
            {
                $this->vehicle= $vehicle;
            }

            public function getDeparture()
            {
                return $this->departure;
            }
            public function setDeparture(  $departure)
            {
                $this->departure = $departure;
            }
    
            public function getArrival() 
            {
                return $this->arrival;
            }
            public function setArrival(  $arrival)
            {
                $this->arrival = $arrival;
            }     

            public function getinvoices(): Collection
            {
                return $this->invoices;
            }
            public function setinvoices(Collection $invoices): void
            {
                $this->invoices = $invoices;
            }

        }
