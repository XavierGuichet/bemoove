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

        $cart = $this->addMember($cart);


        // FIXME: work only for cart with one product, or same business products
        $products = $cart->getProducts();
        if (count($products) > 0) {
          $business = $products[0]->getProduct()->getCoach()->getBusiness();
          $cart->setSeller($business);
        }
    }

    /*
     * Associate Member to cart if it's a logged User
     */
    private function addMember(Cart $cart) {
      $securityToken = $this->securityTokenStorage->getToken();
      if(!$securityToken) {
          return $cart;
      }
      $account = $securityToken->getUser();
      if (!$account instanceof Account) {
          return $cart;
      }
      $person = $account->getPerson();
      $cart->setMember($person);
      return $cart;
    }
}
