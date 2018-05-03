<?php
// src/AppBundle/EventListener/AuthenticationListener.php

namespace Bemoove\AppBundle\EventListener;

use Bemoove\AppBundle\Entity\Account;
use Bemoove\AppBundle\Entity\Person;
use Bemoove\AppBundle\Entity\Place\Address;
use OrderBundle\Entity\Cart;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

class AuthenticationListener
{
  private $securityTokenStorage;
  private $authorizationChecker;
  private $em;

  public function __construct(EntityManagerInterface $em,TokenStorageInterface $securityTokenStorage, AuthorizationCheckerInterface $authorizationChecker)
  {
      $this->em = $em;
      $this->securityTokenStorage = $securityTokenStorage;
      $this->authorizationChecker = $authorizationChecker;
  }

    /**
     * onAuthenticationSuccess
     * Associate cart created with this IP
     * @param     InteractiveLoginEvent $event
     */
    public function onAuthenticationSuccess( InteractiveLoginEvent $event)
    {
      dump('[onAuthenticationSuccess] Init');
      $account = $this->securityTokenStorage->getToken()->getUser();
      if (!$account instanceof Account || true === $this->authorizationChecker->isGranted('ROLE_PARTNER')) {
          return;
      }

      $originIp = $event->getRequest()->getClientIp();

      $cartRepository = $this->em->getRepository('OrderBundle:Cart');

      $cart = $cartRepository->findOneBy(
                          array('originIp' => $originIp,
                                'member' => null),
                          array( 'dateAdd' => 'DESC')
                        );

      if($cart) {
        $this->checkAccountPerson($account);
        $cart->setMember($account->getPerson());
        $this->em->persist($cart);
        $this->em->flush();
        dump('[onAuthenticationSuccess] Cart flush');
      }
    }

    /**
     * This method is a duplicate of a part of GetMyPerson.php
     * A service & a factory would be more appropriate
     */
    private function checkAccountPerson($account) {
      $person = $account->getPerson();

      if($person) {
          if(!$person->getAddress()){
              $address = new Address();
              $this->em->persist($address);
              $person->setAddress($address);
              $this->em->persist($person);
              $this->em->flush();
          }
          return;
      }

      $address = new Address();
      $this->em->persist($address);
      $person = new Person();
      $person->setAddress($address);
      $this->em->persist($person);
      $account->setPerson($person);
      $this->em->persist($account);
      $this->em->flush();
    }

}
