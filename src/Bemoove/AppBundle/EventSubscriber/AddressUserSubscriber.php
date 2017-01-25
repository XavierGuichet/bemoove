<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Bemoove\AppBundle\Entity\Place\Address;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class AddressUserSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => [['addUser', EventPriorities::PRE_WRITE]],
        ];
    }

    //Ajoute l'utilisateur courant au requete POST pour les Entités necessitant un user
    public function addUser(GetResponseForControllerResultEvent $event)
    {
        $address = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();


        if (!$address instanceof Address || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->securityTokenStorage->getToken()->getUser();
        $address->setUser($user);
    }

}
