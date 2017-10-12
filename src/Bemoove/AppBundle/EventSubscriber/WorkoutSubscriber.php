<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Bemoove\AppBundle\Entity\Workout;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class WorkoutSubscriber implements EventSubscriberInterface
{
    private $securityTokenStorage;
    private $em;

    public function __construct(TokenStorageInterface $securityTokenStorage, EntityManager $em)
    {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->em = $em;
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
        // var_dump($object);

        //Ajoute le coach a partir du token
        $account = $this->securityTokenStorage->getToken()->getUser();
        $object->setOwner($account);

        // $BusinessRepository = $this->em->getRepository('BemooveAppBundle:Business');
        //
        // $business = $BusinessRepository->findOneByOwner($account);
        //
        // $object->setOwner($account);
    }

    public function addphoto(GetResponseForControllerResultEvent $event) {
        $method = $event->getRequest()->getMethod();
        $object = $event->getControllerResult();

        if (!$object instanceof Workout || Request::METHOD_POST !== $method) {
            return;
        }
    }

}
