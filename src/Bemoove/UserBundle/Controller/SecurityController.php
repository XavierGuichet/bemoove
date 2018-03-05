<?php
namespace Bemoove\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Bemoove\UserBundle\Entity\BaseUser;
use Bemoove\UserBundle\Form\Type\BaseUserType;

use Symfony\Component\Routing\Annotation\Route;
use Bemoove\AppBundle\Entity\User;

class SecurityController extends Controller
{
    /**
     * @Route("/login_test", name="login_test")
     */
    public function login_checkAction(Request $request)
    {
    }

    public function login_formAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'BemooveUserBundle:security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    private function check()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    private function logout()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    public function registerAction() {
        $form = $this->createForm(BaseUserType::class, new BaseUser());
        return $this->render(
            'BemooveUserBundle:security:register.html.twig',
            array("form" => $form->createView())
        );
    }
}
