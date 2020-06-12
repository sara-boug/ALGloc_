<?php 
        namespace App\Entity;
        use Doctrine\ORM\Mapping as ORM; 
        use DateTime;


        /**
         * @ORM\Entity(repositoryClass="App\Entity\InvoiceRepository") 
         */
        class Invoice  {
            /**
             * @ORM\Id
             * @ORM\GeneratedValue
             * @ORM\Column(type="integer")
             */
            private $id; 
            /** 
             * @ORM\Column(type="time") 
            */
            private $date; 
            /**
             * @ORM\Column(type="float") 
            */
            private $amount ; // the final price
            /**
             * @ORM\Column(type="boolean")
            */
            private $paid; 
            /**
             * @ORM\ManyToOne(targetEntity="App\Entity\Contract") 
             * @ORM\JoinColumn(name="contract" , referencedColumnName="id")
            */
            private $contract; 

            function __construct(DateTime $date , float $amount ,bool $paid )
            {
                $this->date=$date; 
                $this->$amount=$amount; 
                $this -> $paid=$paid; 
                
            }
            public function getid():int{ 
                return $this ->id; 
            }
            public function setid(int $id){ 
            $this->id = $id; 
            }
            public function getdate():DateTime{ 
                return $this ->date; 
            }
            public function setdate(DateTime $date) :void { 
                $this->date= $date; 
            }

            public function getamount():float{ 
                return $this ->amount; 
            }
            public function setamount(float $amount){ 
                $this->amount= $amount; 
            }

            public function getpaid():bool{ 
                return $this ->paid; 
            }
            public function setpaid(bool $paid){ 
                $this->paid= $paid; 
            }
        

            public function getcontract():Contract{ 
                return $this ->contract; 
            }
            public function setcontract(Contract $contract){ 
                $this->contract= $contract; 
            }

            
        

    }
?>