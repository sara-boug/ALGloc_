<?php
    namespace App\Entity;

        use Doctrine\ORM\Mapping as ORM;
        use Hateoas\Configuration\Annotation as Hateoas;
        use JMS\Serializer\Annotation as Serializer;
        /**
         * @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
         *  @Serializer\XmlRoot("vehicle")
         *  @Hateoas\Relation("self" , href="expr('/admin/vehicle/'~ object.getid())"
         * )
         */
        class Vehicle
        {
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
            private $deposit;
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

            /**
             * @ORM\ManyToOne(targetEntity="App\Entity\Model")
             * @ORM\JoinColumn(name="model" , referencedColumnName="id" , nullable=false)
             */

            private $model;
            /**
             *@ORM\ManyToOne(targetEntity="App\Entity\Agency" , inversedBy="vehicles")
            * @ORM\JoinColumn(name="agency" , referencedColumnName="id" , nullable= false)
            */
            private $agency;

            public function getid(): int
            {
                return $this->id;
            }
            public function setid(int $id): void
            {
                $this->id = $id;
            }

            public function getRegistrationNumber(): string
            {
                return $this->registration_number;
            }
            public function setRegistrationNumber(string $registration_number): void
            {
                $this->registration_number = $registration_number;
            }

            public function getRentalPrice(): float
            {
                return $this->rental_price;
            }
            public function setRentalPrice(float $rental_price): void
            {
                $this->rental_price = $rental_price;
            }

            public function getInssurancePrice(): float
            {
                return $this->inssurance_price;
            }
            public function setInssurancePrice(float $inssurance_price): void
            {
                $this->inssurance_price = $inssurance_price;
            }

            public function getDeposit(): float
            {
                return $this->deposit;
            }
            public function setDeposit(float $deposit): void
            {
                $this->deposit = $deposit;
            }

            public function getPassengerNumber(): int
            {
                return $this->passenger_number;
            }
            public function setPassengerNumber(int $passenger_number): void
            {
                $this->passenger_number = $passenger_number;
            }

            public function getImage()
            {
                return $this->image_;
            }
            public function setImage($image_): void
            {
                $this->image_ = $image_;
            }

            public function getSuitcaseNumber(): int
            {
                return $this->suitcase_number;
            }
            public function setSuitcaseNumber(int $suitcase_number): void
            {
                $this->suitcase_number = $suitcase_number;
            }

            public function getState(): string
            {
                return $this->state_;
            }
            public function setState(string $state_): void
            {
                $this->state_ = $state_;
            }

            public function getGearbox(): string
            {
                return $this->gearbox;
            }
            public function setGearbox(string $gearbox): void
            {
                $this->gearbox = $gearbox;
            }

            public function getStatus(): string
            {
                return $this->status_;
            }
            public function setStatus(string $status_): void
            {
                $this->status_ = $status_;
            }

            public function getmodel(): Model
            {
                return $this->model;
            }
            public function setmodel(Model $model): void
            {
                $this->model = $model;
            }

            public function getagency(): Agency
            {
                return $this->agency;
            }
            public function setagency(Agency $agency): void
            {
                $this->agency = $agency;
            }

        }
