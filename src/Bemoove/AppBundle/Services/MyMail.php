<?php

namespace Bemoove\AppBundle\Services;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Assetic\Exception\Exception;


class MyMail {

    protected $mailer;
    protected $router;
    protected $templating;

    private $environment;
    private $client_front_url;

    private $from = "hello@bemoove.fr";
    private $reply = "hello@bemoove.fr";
    private $name = "Bemoove";

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, RouterInterface $router, string $environment, string $client_front_url) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->environment = $environment;
        $this->client_front_url = $client_front_url;
    }

    public function sendBasicEmail($to) {
        $view = null;
        $view = $this->templating->render('BemooveAppBundle:Mailing:basicmail.html.twig', array());
        if (!$view)
            return false;

        // sujet
        $subject = "Bienvenue sur Bemoove";

        return $this->sendMail($subject, $view, $to);
    }

    public function sendWelcomeMemberEmail($to) {
        print($this->environment);
        $view = null;
        $view = $this->templating->render('BemooveAppBundle:Mailing:Welcome/member.html.twig', array());
        if (!$view)
            return false;

        // sujet
        $subject = "Bienvenue sur Bemoove";

        return $this->sendMail($subject, $view, $to);
    }

    public function sendWelcomeCoachEmail($to) {
        $view = null;
        $view = $this->templating->render('BemooveAppBundle:Mailing:Welcome/coach.html.twig', array());
        if (!$view)
            return false;

        // sujet
        $subject = "Bienvenue sur Bemoove";

        return $this->sendMail($subject, $view, $to);
    }

    public function sendForgottenPasswordTokenMail($to, $token = null) {
        $recover_url = $this->client_front_url;
        if($token !== null) {
          $recover_url .= '/recover/set-new-password/'.$token;
        }
        $data = array('recover_url' => $recover_url);

        $view = $this->templating->render('BemooveAppBundle:Mailing:recover-password.html.twig', $data);
        if (!$view)
            return false;

        // sujet
        $subject = "Procédure de changement de mot de passe";

        return $this->sendMail($subject, $view, $to);
    }

    public function sendPasswordChangedMail($to) {
        $view = null;
        $view = $this->templating->render('BemooveAppBundle:Mailing:basicmail.html.twig', array());
        if (!$view)
            return false;

        // sujet
        $subject = "Reussite du changement de mot de passe";

        return $this->sendMail($subject, $view, $to);
    }

    private function sendMail($subject, $view, $to){
        // TODO $view = $this->createOnlineVersion($view);

        //Do Not send mail during behat test
        if($this->environment === 'behat') {
            print 'nomail send';
            return true;
        }

        $mail = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->from, $this->name)
                ->setBody($view)
                ->setReplyTo($this->reply, $this->name)
                ->setContentType('text/html');

        try {
            $mail->setTo($to);
            $this->mailer->send($mail);
        } catch (Exception $exc) {
            return false;
        }

        return true;
    }
}
