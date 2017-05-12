<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Bemoove\AppBundle\Entity\Place\Address;
use Bemoove\AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class ConvertImageSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $securityTokenStorage;

    public function __construct(\Swift_Mailer $mailer, TokenStorageInterface $securityTokenStorage)
    {
        $this->mailer = $mailer;
        $this->securityTokenStorage = $securityTokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['base64ToFile', EventPriorities::PRE_WRITE]],
        ];
    }

    //Transforme l'image en base64 en fichier
    public function base64ToFile(GetResponseForControllerResultEvent $event)
    {
        $address = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();


        if (!$image instanceof Image || Request::METHOD_POST !== $method) {
            return;
        }

        var_dump($image);
        die();

    }
}
