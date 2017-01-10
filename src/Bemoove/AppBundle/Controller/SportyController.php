<?php

namespace Bemoove\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Bemoove\AppBundle\Entity\Profile;
use Bemoove\AppBundle\Form\Type\Sporty\ProfileType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class SportyController extends Controller
{
    public function profileAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repo_user = $em->getRepository('Bemoove\AppBundle\Entity\User');
        $repo_profile = $em->getRepository('Bemoove\AppBundle\Entity\Profile');
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $coach = $repo_user->find($userId);
        $profile = $repo_profile->findOneByUser($coach);

        if(empty($profile)) {
            $profile = new Profile();
            $profile->setUser($coach);
        }

        $form = $this->createForm(ProfileType::class, $profile);
        $form->add('save', SubmitType::class, array(
            'attr' => array('class' => 'save'),
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profile->getPhoto()->upload();
            $em->persist($profile);
            $em->flush();
        }

        return $this->render('BemooveAppBundle:Sporty:profile.html.twig',
                                array(
                                    "form" => $form->createView()
                                    )
                            );
    }

    public function messagerieAction() {
        return $this->render('BemooveAppBundle:Default:index.html.twig');
    }

    public function comingtrainingAction() {
        return $this->render('BemooveAppBundle:Default:index.html.twig');
    }

    public function pasttrainingAction() {
        return $this->render('BemooveAppBundle:Default:index.html.twig');
    }

    public function favoritesAction() {
        return $this->render('BemooveAppBundle:Default:index.html.twig');
    }

    public function directBookingAction($id_session) {
        return $this->render('BemooveAppBundle:Sporty:directbooking.html.twig');
    }
}
