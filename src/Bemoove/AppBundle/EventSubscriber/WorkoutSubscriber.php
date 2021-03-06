<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Bemoove\AppBundle\Entity\Workout;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class WorkoutSubscriber implements EventSubscriberInterface
{
    private $securityTokenStorage;

    public function __construct(TokenStorageInterface $securityTokenStorage)
    {
        $this->securityTokenStorage = $securityTokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['addOwner', EventPriorities::PRE_VALIDATE],
                ['addphoto', EventPriorities::PRE_VALIDATE]
            ]
        ];
    }

    //Ajoute l'utilisateur courant au requete POST pour les Entités necessitant un user
    public function addOwner(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $object = $event->getControllerResult();

        if (!$object instanceof Workout || Request::METHOD_POST !== $method) {
            return;
        }

        //Ajoute le coach a partir du token
        $account = $this->securityTokenStorage->getToken()->getUser();
        $object->setOwner($account);
    }

    public function addphoto(GetResponseForControllerResultEvent $event) {
        $method = $event->getRequest()->getMethod();
        $object = $event->getControllerResult();

        if (!$object instanceof Workout || Request::METHOD_POST !== $method) {
            return;
        }
    }

}
