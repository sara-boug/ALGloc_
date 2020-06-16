<?php
    namespace App\Entity;

    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Component\Security\Core\User\UserInterface;
     /**
     * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
     *
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
        /** @ORM\Column(type="string" , length=200)
         * @Assert\NotBlank
         * @Assert\Email(message ="email is not valid")
         */
        private $email;
        /** @ORM\Column(type="string" , length=200)
         * @Assert\NotBlank
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
        private  $roles =[]; 
        /** @ORM\Column(type="string" , unique =true , nullable=true) */
        private $api_token; 
 
       /* public function __construct1(string $fullname_, string $familyname, string $email, string $password_, string $address,
            string $phone_number, string $license_number) {
            $this->name = $fullname_;
            $this->familyname = $familyname;
            $this->address = $email;
            $this->address = $address;
            $this->phone_number = $phone_number;
            $this->license_number = $license_number;

        } */
        public function __construct()
        {
          
        }
        public function getid()
        {
            return $this->id;
        }
        public function setid(int $id)
        {
            $this->id = $id;
        }

        public function getfullname_()
        {
            return $this->fullname_;
        }
        public function setfullname_(string $fullname_)
        {
            $this->fullname_ = $fullname_;
        }


        public function getaddress():string
        {
            return $this->address;
        }
        public function setaddress(string $address):void
        {
            $this->address = $address;
        }

        public function getemail():string
        {
            return $this->email;
        }
        public function setemail(string $email):void
        {
            $this->email = $email;
        }

        public function getPassword():string // start by P because it's an  abstract method 
        {
            return $this->password;
        }
        public function setPassword(string $password):void
        {
            $this->password = $password;
        }

        public function getphone_number():string
        {
            return $this->phone_number;
        }
        public function setphone_number(string $phone_number):void 
        {
            $this->phone_number = $phone_number;
        }

        public function getlicense_number(): string
        {
            return $this->license_number;
        }
        public function setlicense_number(string $license_number): void
        {
            $this->license_number = $license_number;
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

        public function getapi_token() : string { 
           return $this->api_token; 
        }
        
        public function setapi_token(string $api_token){ 
             $this->api_token = $api_token; 
        }

        
        

      }

?>