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

use Bemoove\AppBundle\Services\MangoPay\ValidationService;
use Bemoove\AppBundle\Services\MangoPayService;

final class MangoPayUserSubscriber implements EventSubscriberInterface
{
  private $em;
  private $mangopayValidation;
  private $mangopay;

  public function __construct(EntityManagerInterface $em, \Bemoove\AppBundle\Services\MangoPay\ValidationService $mangopayValidation, \Bemoove\AppBundle\Services\MangoPay\ApiService $mangopay)
  {
    $this->em = $em;
    $this->mangopayValidation = $mangopayValidation;
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
     if($object instanceof Person) {
       $AccountRepository = $this->em->getRepository('BemooveAppBundle:Account');
       $account = $AccountRepository->findOneByPerson($object);

       // Si une person est lié à un account, c'est que c'est un sporty
       if($account) {
         $object->setEmail($account->getEmail());
         $this->syncMangoPayNaturalUser($object);         
       } else {
         $businessRepository = $this->em->getRepository('BemooveAppBundle:Business');
         $business = $businessRepository->findOneByLegalRepresentative($object);

         if($business) {
           $this->syncMangoPayLegalUser($business);
         }
       }
     } elseif($object instanceof Business) { //Si c'est un business, il n'y a pas besoin de differencié
       $this->syncMangoPayLegalUser($object);
     }
  }

  private function syncMangoPayNaturalUser(Person $person) {
    if($this->mangopayValidation->checkNaturalUserIsReady($person)) {
      if(!$person->getMangoPayId()) {
        $mangoUser = $this->mangopay->createNaturalUser($person);
        $person->setMangoPayId($mangoUser->Id);
        $this->em->persist($person);
        $this->em->flush();
        $mangoWallet = $this->mangopay->createWallet($mangoUser);
      } else {
        $mangoUser = $this->mangopay->updateNaturalUser($person);
      }
    }
  }

  private function syncMangoPayLegalUser(Business $business) {
    if($this->mangopayValidation->checkLegalUserIsReady($business)) {
      if(!$business->getMangoPayId()) {
        $mangoUser = $this->mangopay->createLegalUser($business);
        $business->setMangoPayId($mangoUser->Id);
        $this->em->persist($business);
        $this->em->flush();
        $mangoWallet = $this->mangopay->createWallet($mangoUser);
      } else {
        $mangoUser = $this->mangopay->updateLegalUser($business);
      }
    }
  }
}
