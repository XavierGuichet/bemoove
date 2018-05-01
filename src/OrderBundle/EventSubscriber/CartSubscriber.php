<?php

namespace OrderBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Bemoove\AppBundle\Entity\Account;
use OrderBundle\Entity\Cart;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CartSubscriber implements EventSubscriberInterface
{
    private $securityTokenStorage;

    public function __construct(TokenStorageInterface $securityTokenStorage)
    {
        $this->securityTokenStorage = $securityTokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['addOriginIp', EventPriorities::PRE_WRITE]],
        ];
    }

    // Ajoute l'ip du client à l'objet Cart
    // Ajoute la personne de l'utilisateur connecté si possible
    public function addOriginIp(GetResponseForControllerResultEvent $event)
    {
        $cart = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$cart instanceof Cart || Request::METHOD_POST !== $method) {
            return;
        }

        $originIp = $event->getRequest()->getClientIp();

        $cart->setOriginIp($originIp);

        dump($cart);

        $securityToken = $this->securityTokenStorage->getToken();
        if(!$securityToken) {
          dump('no token');
          return;
        }
        $account = $securityToken->getUser();
        dump($account);
        if (!$account instanceof Account) {
            dump('no account');
            return;
        }
        $person = $account->getPerson();
        $cart->setMember($person);
        dump($cart);
    }
}
