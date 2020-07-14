<?php
   namespace App\Entity;

      use Doctrine\Common\Collections\Collection;
      use Doctrine\ORM\Mapping as ORM;
      use Hateoas\Configuration\Annotation as Hateoas;
      use JMS\Serializer\Annotation as Serializer;

      /**
       * @ORM\Entity(repositoryClass="App\Repository\WilayaRepository")
      *  @Serializer\XmlRoot("Wilaya")
      * @Hateoas\Relation( "self"  ,
      * href= @Hateoas\Route( "get_wilaya", parameters={ "id" = "expr(object.getid())" } )
      * )

      */
      class Wilaya
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
          * @ORM\OneToMany(targetEntity="App\Entity\City" , mappedBy="wilaya")
          */

         private $cities;
         public function __construct()
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

         public function getname(): string
         {
            return $this->name_;
         }
         public function setname(string $name_): void
         {
            $this->name_ = strtolower(trim($name_));
         }

         public function getcities(): Collection
         {
            return $this->cities;
         }
         public function setcities(Collection $cities): void
         {
            $this->cities = $cities;
         }

      }
