<?php 
        namespace App\Entity;
        use Doctrine\ORM\Mapping as ORM; 
        use DateTime;
        use Hateoas\Configuration\Annotation as Hateoas; 
        use JMS\Serializer\Annotation as Serializer;

        /**
         * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository") 
         * @Serializer\XmlRoot("invoice")
         * @Hateoas\Relation("self" , href= 
         * @Hateoas\Route("expr(object.getLink())" , parameters={"id" = "expr(object.getid())"}))
         */
        class Invoice  {
            /**
             * @ORM\Id
             * @ORM\GeneratedValue
             * @ORM\Column(type="integer")
             */
            private $id; 
            /** 
             * @ORM\Column(type="date") 
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
            /** @Serializer\Exclude */

            private  $link = "get_invoice";


            function __construct( )
            {
                 
            }
         // this function allow modifying the links between clients and admins routes
           public function setLink( string  $link )  { 
               $this->link = $link   ;
                
            }
            public function getLink(){ 
                return $this->link;
            }

            public function getid():int{ 
                return $this ->id; 
            }
            public function setid(int $id){ 
            $this->id = $id; 
            }
            public function getdate():DateTime{ 
                return $this ->date_; 
            }
            public function setdate(string $date_) :void { 
                $this->date_=new DateTime( $date_); 
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
        

            public function getcontract():Contract_{ 
                return $this ->contract_; 
            }
            public function setcontract(Contract_ $contract_){ 
                $this->contract_= $contract_; 
            }

            
        

    }
?>