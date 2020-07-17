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

            private $date_;  // this date concerns the contract 
                /**
                 * @ORM\ManyToOne(targetEntity="App\Entity\Client")
                 * @ORM\JoinColumn(name="client" , referencedColumnName="id")
                */
            private $client; 
                /**
                 * @ORM\OneToMany(targetEntity="App\Entity\Invoice" , mappedBy="contract")
                 */
            private $invoices; 
                /**
                 * @ORM\ManyToOne(targetEntity="App\Entity\Agency")
                 * @ORM\JoinColumn(name="arrival_agency" , referencedColumnName="id")
                 */

            function __construct( )
            {
            }
            public function getid():int{ 
                return $this ->id; 
            }
            public function setid(int $id){ 
            $this->id = $id; 
            }
            
            public function getDate():DateTime{ 
                return $this ->date_; 
            }
            public function setDate(DateTime $date_){ 
            $this->date_= $date_; 
            }         

            function getclient() :Client{ 
                return $this->client; 
            }
            function setclient(Client $client) :void { 
                    $this->client = $client;
            }
 

            function getinvoices() :Collection { 
                return $this->invoices; 
            }
            function setinvoices(Collection $invoices) :void { 
                    $this->invoices = $invoices;
            }

 
    }
