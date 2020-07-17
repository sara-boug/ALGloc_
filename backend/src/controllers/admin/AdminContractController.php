<?php
namespace App\controllers\admin;
use App\Entity\Contract_;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\CallableUrlGenerator;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use  Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;



class AdminContractController extends  AbstractController { 
    
    private $hateoas; 
    public function __construct( RouterInterface $router)
    {
      
        $this->hateoas= HateoasBuilder::create()
             ->setUrlGenerator(
                null , 
                new CallableUrlGenerator(
                function($route , $parameter , $absolute) use ($router)  { 
                 return $router->generate($route ,  $parameter, RouterInterface::ABSOLUTE_URL); 
               })
             ) ->build(); 
        
    }
  /*   public function jsonToContractObject( $body) :Contract_ { 
          $contract = new Contract_(); 
 
     }
    /** @Route( "/admin/contract"  , name="post_contract" , methods ={"POST"} ) */
   /* public function postContract( Request $request ) : Response{ 
         $body= json_decode( $request->getContent(), true ) ; 


    }*/

}

?> 