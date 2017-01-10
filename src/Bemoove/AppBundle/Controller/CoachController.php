<?php

namespace Bemoove\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Bemoove\AppBundle\Entity\Profile;
use Bemoove\AppBundle\Entity\TrainingSession;
use Bemoove\AppBundle\Form\Type\Sporty\ProfileType;
use Bemoove\AppBundle\Form\Type\TrainingSessionType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CoachController extends Controller
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

            $profile->getAvatar()->setOwner($coach)->setKind("avatar");
            $slug = $this->get('app.slugger')->slug_it($profile->getFirstName()."-".$profile->getLastName());
            $profile->getAvatar()->setSlug_name($slug);
            $profile->getAvatar()->upload();

            $em->persist($profile);
            $em->flush();
        }

        return $this->render('BemooveAppBundle:Coach:profile.html.twig',
                                array(
                                    "form" => $form->createView()
                                    )
                            );
    }

    public function mytrainingAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo_user = $em->getRepository('Bemoove\AppBundle\Entity\User');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $coach = $repo_user->find($userId);

        $repo_trainingSession = $em->getRepository('Bemoove\AppBundle\Entity\TrainingSession');
        $trainingSessionList = $repo_trainingSession->findByCoach($coach);

        $twig['trainingSessionList'] = $trainingSessionList;

        return $this->render('BemooveAppBundle:Coach:mytraining.html.twig',$twig);
    }
    public function addtrainingAction(Request $request, $idtrainingsession = null)
    {
        $em = $this->getDoctrine()->getManager();
        if(($coach = $this->findCurrentCoach()) === null) {
            throw new \Exception("Le coach n'a pas pu être trouvé.");
        };

        if($idtrainingsession) {
            $repo_trainingSession = $em->getRepository('Bemoove\AppBundle\Entity\TrainingSession');
            $trainingSession = $repo_trainingSession->find($idtrainingsession);
        }
        else {
            $trainingSession = new TrainingSession();
        }

        $form = $this->createForm(TrainingSessionType::class, $trainingSession);
        $form->add('save', SubmitType::class, array(
            'attr' => array('class' => 'save'),
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trainingSession->setCoach($coach);
            if (null !== $trainingSession->getPhoto()->getFile()) {
                $trainingSession->getPhoto()->setOwner($coach)->setKind("trainingsession");
                $slug = $this->get('app.slugger')->slug_it($trainingSession->getTitle());
                $trainingSession->getPhoto()->setSlug_name($slug);
                $trainingSession->getPhoto()->upload();
                $trainingSession->getPhoto()->setOwner($coach)->setKind("trainingsession")->upload();
            } else {
                $trainingSession->setPhoto(null);
            }
            $em->persist($trainingSession);
            $em->flush();
            return $this->redirectToRoute('bemoove_app_coach_mytraining');
        }

        return $this->render('BemooveAppBundle:Coach:add-training.html.twig',
                                array(
                                    "form" => $form->createView()
                                )
                            );
    }
    public function dashboardAction()
    {
        return $this->render('BemooveAppBundle:Coach:dashboard.html.twig');
    }

    private function findCurrentCoach() {
        $em = $this->getDoctrine()->getManager();
        $repo_user = $em->getRepository('Bemoove\AppBundle\Entity\User');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $coach = $repo_user->find($userId);
        return $coach;
    }

    public function galleryAction() {
        $em = $this->getDoctrine()->getManager();
        $repo_user = $em->getRepository('Bemoove\AppBundle\Entity\User');
        $repo_image = $em->getRepository('Bemoove\AppBundle\Entity\Image');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $coach = $repo_user->find($userId);

        $gallery = $repo_image->findByOwner($coach);

        $twig['gallery'] = $gallery;


        return $this->render('BemooveAppBundle:Coach:gallery.html.twig',$twig);

    }
}
