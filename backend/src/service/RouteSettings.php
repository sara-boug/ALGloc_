<?php
    namespace App\service;

        use Hateoas\Representation\PaginatedRepresentation;
        use App\Entity\Client; 
        use  Doctrine\ORM\EntityManager; 


        class RouteSettings
        {
            public function __construct()
            {

            }
             // in order to  use this functionality as a service in  all the settings
            // returns a json object containing the collection of object according to their selection
            public function pagination(array $object, string $routeName)
            {
                $limit = 10;
                //getting the exact count of the total of the  pages
                $total = $object % $limit == 0 ? count($object) / $limit : (count($object) / $limit) + 1;
                $objectPag = new PaginatedRepresentation( // enabling paginations
                    $object,
                    $routeName,
                    array(),
                    1,
                    $limit,
                    $total,
                    'page',
                    'limit',
                     false,
                    count($object), $absolute = true);
                return $objectPag;
            }

             // function which will be used to generate a current user of the client routes 
            public function getCurrentClient(EntityManager $em , $user):Client
            { 
                 $client =$em->getRepository(Client::class)->findOneBy(['email' =>$user->getemail()]); 
                 return $client; 
            }
    

        }
