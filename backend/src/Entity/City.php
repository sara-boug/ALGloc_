<?php
     namespace App\Entity;

          use Doctrine\Common\Collections\Collection;
          use JMS\Serializer\Annotation as Serializer;
          use Hateoas\Configuration\Annotation as Hateoas; 
          use  Doctrine\ORM\Mapping as ORM ;

          /**
           * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
           * @Serializer\XmlRoot("city")
           * @Hateoas\Relation("self" , 
           * href=@Hateoas\Route("get_city" , parameters ={"id" ="expr(object.getid())"}) 
           * )
          */
          class City
          {
          /**
               * @ORM\Id;
               * @ORM\GeneratedValue
               * @ORM\Column(type="integer")
               */
          private $id;
          /**
               * @ORM\Column(type="string" , length=200)
               */
          private $name_;
          /**
               *@ORM\ManyToOne(targetEntity="App\Entity\Wilaya")
               *@ORM\JoinColumn(name="wilaya" , referencedColumnName="id")
               */
          private $wilaya;
          /**
               * @ORM\OneToMany(targetEntity="App\Entity\Client" , mappedBy="city")
               */
          /** @Serializer\Exclude */

          private $clients;
          /**
               * @ORM\OneToMany(targetEntity="App\Entity\Agency" , mappedBy="city")
               */
          /** @Serializer\Exclude */

          private $agencies;
          public function __construct( )
          {

          }
          public function getid(): int
          {

               return $this->id;
          }
          public function setid(int $id): void
          {
               $this->id = $id;
          }

          public function getName(): string
          {

               return $this->name_;
          }
          public function setName(string $name_): void
          {
               $this->name_ = strtolower(trim($name_));
          }

          public function getWilaya(): Wilaya
          {
               return $this->wilaya;
          }
          public function setWilaya(Wilaya $wilaya): void
          {
               $this->wilaya = $wilaya;
          }

          public function getClients(): Collection
          {
               return $this->clients;
          }
          public function setClients(Collection $clients): void
          {
               $this->clients = $clients;
          }

          public function getAgencies(): Collection
          {
               return $this->agencies;
          }
          public function setAgencies(Collection $agencies): void
          {
               $this->agencies = $agencies;
          }

          }
