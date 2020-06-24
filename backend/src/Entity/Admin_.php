<?php
    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Component\Security\Core\User\UserInterface;
    /**
     * @ORM\Entity(repositoryClass="App\Repository\Admin_Repository")
     *
     */
    class Admin_  implements UserInterface
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
        private $email;
        /** @ORM\Column(type="string" , length=200)
         * @Assert\NotBlank
         */
        private $password;
        /**
         * @ORM\Column(type="json")
         */
        private $roles = [] ; 
   

        public function __construct()
        {
          
        }
        public function getid():int
        {
            return $this->id;
        }
        public function setid(int $id):void
        {
            $this->id = $id;
        }
        public function getemail(): string
        {
            return $this->email;
        }
        public function setemail(string $email):void
        {
            $this->email = $email;
        }

        public function getpassword():String
        {
            return $this->password;
        }
        public function setpassword(string $password):void
        {
            $this->password = $password;
        }


        public function getRoles() :array
        {
            $roles = $this->roles; 
            $roles [] = 'ROLE_ADMIN'; 
            return  array_unique($roles); 

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