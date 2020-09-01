<?php
  namespace App\Entity;

    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Component\Security\Core\User\UserInterface;
    use  Hateoas\Configuration\Annotation as Hateoas; 
    use JMS\Serializer\Annotation as Serializer; 
    /**
     * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
     * @Serializer\XmlRoot("client")
     * @Hateoas\Relation("self" , 
     * href= @Hateoas\Route( "expr(object.getLink())", parameters = {"id" = "expr(object.getid())"}))
     */
    class Client  implements UserInterface
    {
        
        /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         */
        private $id;
        /** @ORM\Column(type="string" , length=200)
         * @Assert\NotBlank
         */
        private $fullname_;
        /** @ORM\Column(type="string" , length=200 , unique =true )
         * @Assert\NotBlank
         * @Assert\Email(message ="email is not valid")
         */
        private $email;
        /** 
         * @ORM\Column(type="string" , length=200)
         * @Assert\NotBlank
         *  @Serializer\Exclude 
         */
         

        private $password;
        /** @ORM\Column(type="string" , length=300)
         * @Assert\NotBlank
         */
        private $address;
        /** @ORM\Column(type="string" , length=200)
         * @Assert\NotBlank
         */
        private $phone_number;
        /** @ORM\Column(type="string" , length=200)
         * @Assert\NotBlank
         */
        private $license_number;

        /**
         * @ORM\ManyToOne(targetEntity="App\Entity\City")
         * @ORM\JoinColumn(name="city" , referencedColumnName="id")
         */
        private $city;
        /** @ORM\OneToMany(targetEntity="App\Entity\Contract_" , mappedBy="client")*/
        private $contracts;
        /**@ORM\Column(type="json") */
        /** @Serializer\Exclude */

        private  $roles =[]; 
        /** @ORM\Column(type="text" , nullable= true ) 
        */

        private $api_token; 
       /**  @Serializer\Exclude  */ 

        private $link="get_client" ;  // the default is to return the admin client route
 

        public function __construct()
        {
          
        } 
        // this function allow modifying the links between clients and admins
        public function setLink(   $link )  { 
              
               $this->link = $link   ;
            
        }
        public function getLink(){ 
            return $this->link;
        }
        public function getid()
        {
            return $this->id;
        }
        public function setid(int $id)
        {
            $this->id = $id;
        }

        public function getfullname()
        {
            return $this->fullname_;
        }
        public function setfullname(string $fullname_)
        {
            $this->fullname_ =strtolower( trim( $fullname_));
        }


        public function getaddress():string
        {
            return $this->address;
        }
        public function setaddress(string $address):void
        {
            $this->address = trim($address);
        }

        public function getemail():string
        {
            return $this->email;
        }
        public function setemail(string $email):void
        {
            $this->email = trim($email);
        }

        public function getPassword():string // start by P because it's an  abstract method 
        {
            return $this->password;
        }
        public function setPassword(string $password):void
        {
            $this->password =trim( $password);
        }

        public function getphoneNumber():string
        {
            return $this->phone_number;
        }
        public function setphoneNumber(string $phone_number):void 
        {
            $this->phone_number = trim($phone_number);
        }

        public function getlicenseNumber(): string
        {
            return $this->license_number;
        }
        public function setlicenseNumber(string $license_number): void
        {
            $this->license_number =trim( $license_number);
        }

        public function getcity(): City
        {
            return $this->city;
        }
        public function setcity(City $city): void
        {
            $this->city = $city;
        }

        public function getcontracts(): Collection
        {
            return $this->contracts;
        }
        public function setcontracts(Collection $contracts): void
        {
            $this->contracts = $contracts;
        }

        public function getRoles(): array
        {
            $roles= $this->roles; 
            $roles [] = 'ROLE_USER'; 
            return array_unique( $roles); 
        }
        

        public function  eraseCredentials()
        {
            
        }

        public function getSalt()
        {
            
        }
         
        public function getUsername()
        {
            
        }

        public function getapi_token()  { 
           return $this->api_token; 
        }
        
        public function setapi_token($api_token){ 
             $this->api_token = $api_token; 
        }

        
        

      }

?>