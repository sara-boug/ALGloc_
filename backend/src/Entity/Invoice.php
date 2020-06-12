<?php 
    namespace App\Entity;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM; 
    /**@ORM/Entity(repositoryName="App\Entity\InvoiceRepository") */
    class Invoice  {
        /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         */
        private $id; 
        /** @ORM/Column(type="time") */
        private $date; 
        /**@ORM/Column(type="float") */
        private $amount ; // the final price
        /**@ORM/Column(type="boolean") */
        private $paid; 
        /**
         * @ORM\OneToOne(targetEntity="App\Entity\Contract") 
         * @ORM\JoinColumn(name="id_Contract" , referencedColumnName="id")
        */
        private $id_Contract; 
        public function getid():int{ 
            return $this ->id; 
        }
        public function setid(int $id){ 
        $this->id = $id; 
        }
        public function getdate():DateTime{ 
            return $this ->date; 
        }
        public function setdate(DateTime $date){ 
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
       

        public function getid_Contract():int{ 
            return $this ->id_Contract; 
        }
        public function setid_Contract(int $id_Contract){ 
            $this->id_Contract= $id_Contract; 
        }

        
    

}

?>