<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use Bemoove\AppBundle\Entity\Account;
use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Person;

use Bemoove\AppBundle\Services\MangoPayService;

final class MangoPayUserSubscriber implements EventSubscriberInterface
{
  private $em;
  private $mangopay;

  public function __construct(EntityManagerInterface $em, MangoPayService $mangopay)
  {
    $this->em = $em;
    $this->mangopay = $mangopay;
  }

  public static function getSubscribedEvents()
  {
      return [
          KernelEvents::VIEW => [
                                ['syncMangoPayUser', EventPriorities::POST_WRITE]
      ]
      ];
  }

  public function syncMangoPayUser(GetResponseForControllerResultEvent $event)
  {
    $object = $event->getControllerResult();
    $method = $event->getRequest()->getMethod();

    // Seul les business et les personnes sont à synchro
    if (!$object instanceof Person && !$object instanceof Business) {
      return;
    }

    // Seuls les créations ou les mise à jour sont à synchro
    if (Request::METHOD_POST !== $method && Request::METHOD_PUT !== $method) {
      return;
    }

    /*
     * Synchro les business, les legals users, les natural users
     * Les créer s'il n'existent pas encore
     * Les updates s'il existent
     */

     //Si c'est une personne ont check que ce n'est pas un legal users
     if($object instanceof Person) {
       $AccountRepository = $this->em->getRepository('BemooveAppBundle:Account');

       $account = $AccountRepository->findOneByPerson($object);

       if($account) {
         if (Request::METHOD_POST === $method) {
           $mangoUser = $this->mangopay->createNaturalUser($object);
         }
         if (Request::METHOD_PUT === $method) {
           $mangoUser = $this->mangopay->updateNaturalUser($object);
         }
       }
     }
     //Si c'est une personne ont check qu'il est lié à un Account

     //Si c'est un business, c'est tjrs good
  }
}
