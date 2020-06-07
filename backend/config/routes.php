<?php 
use  Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator; 

 return function(RoutingConfigurator $route){ 
   $route -> import('.' , 'signup'); 
          

 };


?>