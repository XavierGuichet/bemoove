<?php

namespace Bemoove\AppBundle\Services;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Assetic\Exception\Exception;


class MyMail {

    protected $mailer;
    protected $router;
    protected $templating;

    private $from = "hello@bemoove.fr";
    private $reply = "hello@bemoove.fr";
    private $name = "Bemoove";

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, RouterInterface $router) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
    }

    public function sendBasicEmail($to) {
        $view = null;
        $view = $this->templating->render('BemooveAppBundle:Mailing:basicmail.html.twig', array());
        if (!$view)
            return false;

        // sujet
        $subject = "[Test Mail] Basic";

        return $this->sendMail($subject, $view, $to);
    }

    public function sendWelcomeCoachEmail($to) {
        $view = null;
        $view = $this->templating->render('BemooveAppBundle:Mailing:Welcome/coach.html.twig', array());
        if (!$view)
            return false;

        // sujet
        $subject = "[Test Mail] Bienvenue sur Bemoove";

        return $this->sendMail($subject, $view, $to);
    }

    private function sendMail($subject, $view, $to){
        // TODO $view = $this->createOnlineVersion($view);

        // pour utiliser la fonction php mail à la place du smtp
        //$transport = \Swift_MailTransport::newInstance();
        //$this->mailer = \Swift_Mailer::newInstance($transport);

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
