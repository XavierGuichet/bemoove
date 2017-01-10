<?php

namespace Bemoove\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ProfileController extends Controller
{
    public function displayAction($iduser)
    {
        $em = $this->getDoctrine()->getManager();
        $repo_profile = $em->getRepository('Bemoove\AppBundle\Entity\Profile');
        $user = $repo_profile->findOneByUser($iduser);

        $twig['user'] = $user;

        return $this->render('BemooveAppBundle:Default:profile.html.twig', $twig);
    }

}
