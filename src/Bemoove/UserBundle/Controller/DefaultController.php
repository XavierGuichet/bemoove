<?php

namespace Bemoove\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BemooveUserBundle:Default:index.html.twig');
    }
}
