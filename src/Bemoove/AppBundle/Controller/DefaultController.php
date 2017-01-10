<?php

namespace Bemoove\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Bemoove\AppBundle\Entity\User;
use Bemoove\AppBundle\Entity\Profile;
use Bemoove\AppBundle\Form\Type\UserType;
use Bemoove\AppBundle\Form\Type\TrainingSearchType;
use Bemoove\AppBundle\Form\Type\FullRegistrationType;


class DefaultController extends Controller
{
    public function indexAction()
    {
        // $search_form = $this->createForm(TrainingSearchType::class, null, array(
        //                 'action' => $this->generateUrl('bemoove_app_search_result'),
        //                 'method' => 'POST',
        //             ));
        // $twig['search_form'] =$search_form->createView();
        //
        // $em = $this->getDoctrine()->getManager();
        // $repo_trainingSession = $em->getRepository('Bemoove\AppBundle\Entity\TrainingSession');
        // $trainingSessionList = $repo_trainingSession->findBy(array(), null, 5, 0);
        // $twig['trainingSessions'] = $trainingSessionList;
        //
        // return $this->render('BemooveAppBundle:Default:index.html.twig',$twig);
    }

    public function registerAction(Request $request) {
        // $user = new User();
        // $form = $this->createForm(UserType::class, $user);
        // $form->add('save', SubmitType::class, array('label' => 'Create my account'));
        //
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
        //     $user->setPassword($password);
        //
        //     $role = "ROLE_".strtoupper($form->get('Roles')->getData());
        //     $user->addRoles($role);
        //
        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($user);
        //     $em->flush();
        //
        //     return $this->redirectToRoute('bemoove_app_homepage');
        // }
        //
        //
        // return $this->render(
        //     'BemooveUserBundle:security:register.html.twig',
        //     array("form" => $form->createView())
        // );
    }

    public function registerfullAction(Request $request) {
        // $user = new User();
        // $user->setProfile(new Profile());
        //
        // $form = $this->createForm(FullRegistrationType::class, $user);
        // $form->add('save', SubmitType::class, array('label' => 'Create my account'));
        //
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
        //     $user->setPassword($password);
        //
        //     $role = "ROLE_".strtoupper($form->get('Roles')->getData());
        //     $user->addRoles($role);
        //     $user->getProfile()->setUser($user);
        //
        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($user);
        //     $em->flush();
        //
        //     return $this->redirectToRoute('bemoove_app_homepage');
        // }
        //
        // return $this->render(
        //     'BemooveAppBundle:Default:fullregister.html.twig',
        //     array("form" => $form->createView())
        // );
    }
}
