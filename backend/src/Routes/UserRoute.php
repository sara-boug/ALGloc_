<?php
namespace App\Routes;

use RuntimeException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class UserRoute extends  Loader { 
      
     private $isLoaded = false; 
     public function load($resource ,  $type=null){ 
           if($this->isLoaded === true) { 
                throw   new RuntimeException("user Route can not be used twice");  
             }          
             $routes = new RouteCollection(); 
             $path ='/signup'; 
             $methods= ['Get']; 
             $defaults = [
                 '_controller' => 'App\Controller\UserController::signup'
             ]; 
             $route=  new Route($path , $defaults ,$methods );
             $routes ->add( 'signup', $route); 

             $this->isLoaded = true; 
             return $routes;       
     }
    
     public function supports($resource,$type = null)
     {
         return  'signup'=== $type ; 
     }

}


?>