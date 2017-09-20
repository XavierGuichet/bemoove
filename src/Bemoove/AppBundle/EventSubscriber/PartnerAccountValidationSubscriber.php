<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;


use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\People;
use Bemoove\AppBundle\Entity\BankAccount;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class PartnerAccountValidationSubscriber implements EventSubscriberInterface
{
    private $securityTokenStorage;

    public function __construct(TokenStorageInterface $securityTokenStorage)
    {
        $this->securityTokenStorage = $securityTokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['checkValidation', EventPriorities::POST_WRITE]]
        ];
    }

    //Ajoute l'utilisateur courant au requete POST pour les Entités necessitant un user
    public function checkValidation(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $object = $event->getControllerResult();

        if (Request::METHOD_POST !== $method || Request::METHOD_PUT !== $method) {
            return;
        }

        //Business Ok ?
        //invoice Settings Ok ?
        if ($object instanceof Business) {

        }

        //LegalRepresentative Ok ?
        if ($object instanceof People) {

        }

        //bankAccount Ok ?
        if ($object instanceof BankAccount) {
            if ($object->getOwnerName() == null) {
                return "ownerName error";
            }
            if ($object->getIban() == null) {
                return "getIban error";
            }
        }

        // TODO : billingMandate Ok ?
        // if ($object instanceof Business) {
        //
        // }

        return;
    }

}
