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
        private $name_;
        /** @ORM\Column(type="string" , length=200)
         * @Assert\NotBlank
         */
        private $familyname;
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
       /* public function __construct1(string $name_, string $familyname, string $email, string $password, string $address,
            string $phone_number, string $license_number) {
            $this->name = $name_;
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

        public function getname_()
        {
            return $this->name_;
        }
        public function setname_(string $name_)
        {
            $this->name_ = $name_;
        }

        public function getfamilyname()
        {
            return $this->familyname;
        }
        public function setfamilyname(string $familyname)
        {
            $this->familyname = $familyname;
        }

        public function getaddress()
        {
            return $this->address;
        }
        public function setaddress(string $address)
        {
            $this->address = $address;
        }

        public function getemail()
        {
            return $this->email;
        }
        public function setemail(string $email)
        {
            $this->email = $email;
        }

        public function getpassword()
        {
            return $this->password;
        }
        public function setpassword(string $password)
        {
            $this->password = $password;
        }

        public function getphone_number()
        {
            return $this->phone_number;
        }
        public function setphone_number(string $phone_number)
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

        public function getRoles()
        {
            
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
        
        
        

      }

?>