<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Bemoove\AppBundle\Entity\BankAccount;
use Bemoove\AppBundle\Entity\Account;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class BankAccountSubscriber implements EventSubscriberInterface
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
                ['addOwner', EventPriorities::PRE_VALIDATE]
            ]
        ];
    }

    //Ajoute l'utilisateur courant au requete POST pour les Entités necessitant un user
    public function addOwner(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $object = $event->getControllerResult();

        if (!$object instanceof BankAccount || Request::METHOD_POST !== $method) {
            return;
        }

        //Ajoute l'utilisateur a partir du token
        $account = $this->securityTokenStorage->getToken()->getUser();
        if (!$account instanceof Account) {
            return;
        }
        $object->setOwner($account);
    }
}
