<?php

namespace Bemoove\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BemooveAdminBundle:Default:index.html.twig');
    }
}
