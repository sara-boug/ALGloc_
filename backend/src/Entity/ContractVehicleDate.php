<?php
   namespace App\Entity; 
   use DateTime; 
   use Doctrine\ORM\Mapping as ORM;  
    /**
     * @ORM\Entity(repositoryClass="App\Repository\ContractVhicleDateRepository")
     *
     */
    class ContractVhicleDate {
        /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         */
        private $id;
        /** @ORM\Column(type="time" )*/

        private $departure;
        /** @ORM\Column(type="time" )*/

        private $arrival;
        /** 
         * @ORM\ManyToOne(targetEntity="App\Entity\Contract_") 
         * @ORM\JoinColumn(name="contract"  , referencedColumnName="id" , nullable=false)
        */
        private $contract_; 
        
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

        public function getNumber(): int
        {
            return $this->number_;
        }
        public function setNumber(int $number_): void
        {
            $this->number_ = $number_;
        }

        public function getDeparture(): DateTime
        {
            return $this->departure;
        }
        public function setDeparture(DateTime $departure)
        {
            $this->departure = $departure;
        }

        public function getArrival(): DateTime
        {
            return $this->arrival;
        }
        public function setArrival(DateTime $arrival)
        {
            $this->arrival = $arrival;
        }


        public function getContract(): Contract_
        {
            return $this->contract_;
        }
        public function setContract(Contract_ $contract_)
        {
            $this->contract_ = $contract_;
        }


        public function getVehicle(): Vehicle
        {
            return $this->vehicle;
        }
        public function setVehicle(Vehicle $vehicle)
        {
            $this->vehicle= $vehicle;
        }
    }
    ?>