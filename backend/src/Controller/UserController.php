<?php 
    namespace   App\Controller;
    use Symfony\Component\HttpFoundation\Response; 
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
    class UserController   extends AbstractController{ 

        public function signup(){ 
            return new Response(
                'i guess it works here'
            ); 
        }


    }
    




?>