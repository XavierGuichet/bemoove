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
            KernelEvents::VIEW => [['addCoach', EventPriorities::PRE_VALIDATE]],
            KernelEvents::VIEW => [['addphoto', EventPriorities::PRE_VALIDATE]],
        ];
    }

    public function transform(GetResponseEvent $event) {
        $object = $event->getRequest()->attributes->get('data');
        // var_dump($object);

    }

    //Ajoute l'utilisateur courant au requete POST pour les Entités necessitant un user
    public function addCoach(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $object = $event->getControllerResult();

        if (!$object instanceof Workout || Request::METHOD_POST !== $method) {
            return;
        }
        // var_dump($object);

        //Ajoute le coach a partir du token
        $coach = $this->securityTokenStorage->getToken()->getUser();
        $object->setCoach($coach);

        // $sport = $object->getSport();
        // var_dump($object);

        // var_dump($object->getAddress());
        // die();
        // $city = $object->getCity();
        // var_dump($city);
        // die($city);
    }

    public function addphoto(GetResponseForControllerResultEvent $event) {
        $method = $event->getRequest()->getMethod();
        $object = $event->getControllerResult();

        if (!$object instanceof Workout || Request::METHOD_POST !== $method) {
            return;
        }

        // var_dump($object);
        // die();
    }

}
