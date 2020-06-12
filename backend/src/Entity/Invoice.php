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
            private $date_; 
            /**
             * @ORM\Column(type="float") 
            */
            private $amount ; // the final price
            /**
             * @ORM\Column(type="boolean")
            */
            private $paid; 
            /**
             * @ORM\ManyToOne(targetEntity="App\Entity\Contract_") 
             * @ORM\JoinColumn(name="contract_" , referencedColumnName="id")
            */
            private $contract_; 

            function __construct(DateTime $date_ , float $amount ,bool $paid )
            {
                $this->date=$date_; 
                $this->$amount=$amount; 
                $this -> $paid=$paid; 
                
            }
            public function getid():int{ 
                return $this ->id; 
            }
            public function setid(int $id){ 
            $this->id = $id; 
            }
            public function getdate_():DateTime{ 
                return $this ->date_; 
            }
            public function setdate_(DateTime $date_) :void { 
                $this->date_= $date_; 
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
        

            public function getcontract_():Contract_{ 
                return $this ->contract_; 
            }
            public function setcontract_(Contract_ $contract_){ 
                $this->contract_= $contract_; 
            }

            
        

    }
?>