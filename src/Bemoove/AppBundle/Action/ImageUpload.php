<?php

// src/Bemoove/AppBundle/Action/ImageUpload.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Image;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\Response;

class ImageUpload
{
    private $myService;

    // public function __construct(MyService $myservice) {
    public function __construct() {
        // var_dump("FUCK");
        // $this->myService = $myService;
    }


    public function __invoke($data)
    {
        var_dump($data);
        return new Response("Hello World !");
        $this->myService->doSomething($data);

        return $data; // API Platform will automatically validate, persist (if you use Doctrine) and serialize an entity
                      // for you. If you prefer to do it yourself, return an instance of Symfony\Component\HttpFoundation\Response
    }


}
