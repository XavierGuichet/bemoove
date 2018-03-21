<?php
// src/AppBundle/EventListener/AuthenticationListener.php

namespace Bemoove\AppBundle\EventListener;

use Bemoove\AppBundle\Entity\Account;
use Bemoove\AppBundle\Entity\Cart;

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
      dump('interactive login listener launched');
      $account = $this->securityTokenStorage->getToken()->getUser();
      if (!$account instanceof Account || true === $this->authorizationChecker->isGranted('ROLE_PARTNER')) {
          return;
      }

      $originIp = $event->getRequest()->getClientIp();

      $cartRepository = $this->em->getRepository('BemooveAppBundle:Cart');

      $cart = $cartRepository->findOneBy(
                          array('originIp' => $originIp,
                                'member' => null),
                          array( 'dateAdd' => 'DESC')
                        );

      if($cart) {
        $cart->setMember($account->getPerson());
        $this->em->persist($cart);
        $this->em->flush();
      }
    }

}
