<?php 
namespace App\controllers\client; 

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\RouterInterface; 
    use Hateoas\HateoasBuilder; 
    use Hateoas\UrlGenerator\CallableUrlGenerator; 
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface; 
    
    
    class ClientAgency extends AbstractController{ 
        public function __construct(  RouterInterface $router)
        {
            $this->hateoas= HateoasBuilder::create()
            ->setUrlGenerator(
                null , 
                new CallableUrlGenerator(function($route , array $parameter , $absolute) use ( $router ){ 
                        return $router->generate($route , $parameter , UrlGeneratorInterface::ABSOLUTE_URL);
                })
            )->build(); 

        }
        
    }




?> 