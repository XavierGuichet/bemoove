<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Bemoove\AppBundle\Entity\Account;
use Bemoove\AppBundle\Entity\Place\Address;
use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\BillingMandate;
use Bemoove\AppBundle\Entity\Person;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;


final class CheckPartnerValidity implements EventSubscriberInterface
{
  private $securityTokenStorage;
  private $em;

  public function __construct(TokenStorageInterface $securityTokenStorage, EntityManagerInterface $em)
  {
      $this->securityTokenStorage = $securityTokenStorage;
      $this->em = $em;
  }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['CheckPartnerValidity', EventPriorities::POST_WRITE]],
        ];
    }

    public function CheckPartnerValidity(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $object = $event->getControllerResult();

        $isAlteringMethod = Request::METHOD_POST === $method || Request::METHOD_PUT === $method;
        $entityShouldBeCheck = $object instanceof BillingMandate || $object instanceof Business || $object instanceof Person;

        if (!$entityShouldBeCheck || !$isAlteringMethod) {
            return;
        }

        //Recupère l'utilisateur a partir du token
        $account = $this->securityTokenStorage->getToken()->getUser();
        if (!$account instanceof Account) {
            return;
        }


        $BusinessRepository = $this->em->getRepository('BemooveAppBundle:Business');

        $business = $account->getBusiness();

        if(!$business instanceof Business) { //business not found
            return;
        }

        // Fi the modified Object is a person, Check if this person is the legalRepresentative
        if($object instanceof Person) {
          if($business->getLegalRepresentative()->getId() !== $object->getId()) {
            return;
          }
        }

        $currentValidity = $business->getIsValid();

        $isBillingMandateSigned = $this->checkBillingMandate($business);

        $areBusinessInfoCompleted = $this->checkBusinessInfo($business);

        $newValidity = $isBillingMandateSigned && $areBusinessInfoCompleted;
        if($newValidity !== $currentValidity) {
          $business->setIsValid($newValidity);
          $this->em->persist($business);
          $this->em->flush();
        }
    }

    private function checkBillingMandate(Business $business) {
        $billingMandateRespository = $this->em->getRepository('BemooveAppBundle:BillingMandate');

        $billingMandate = $billingMandateRespository->findOneByBusiness($business);

        if(!$billingMandate) {
          return false;
        }

        $signOn = $billingMandate->getSignOn();

        return ($signOn instanceof \DateTime);
    }

    private function checkBusinessInfo(Business $business) {
        $areBusinessInfoCompleted = true;

        $areBusinessInfoCompleted &= $this->checkGenericInfo($business); // Nom et Siret

        $areBusinessInfoCompleted &= $this->checkLegalRepresentative($business); // Nom, Prenom, addresse mail et birthdate

        $areBusinessInfoCompleted &= $this->checkInvoiceAddress($business); // Firstline, PostalCode, City

        $areBusinessInfoCompleted &= $this->checkInvoiceLegalNotice($business); // taux de tva, forme juridique, siret, ape, N° TVA, RCS, capital

        return $areBusinessInfoCompleted;
    }

    private function checkGenericInfo(Business $business) {
        $return = true;

        $return &= !empty($business->getLegalName());
        $return &= !empty($business->getSiret());

        return $return;
    }

    private function checkLegalRepresentative(Business $business) {
      $legalRepresentative = $business->getLegalRepresentative();
      if(!$legalRepresentative instanceof Person) {
        return false;
      }

      $return = true;

      $return &= !empty($legalRepresentative->getFirstname());
      $return &= !empty($legalRepresentative->getLastname());
      $return &= !empty($legalRepresentative->getEmail());
      $return &= $legalRepresentative->getBirthdate() instanceof \DateTime;

      return $return;
    }

    private function checkInvoiceAddress(Business $business) {
      $address = $business->getAddress();
      if(!$address instanceof Address) {
        return false;
      }
      $return = true;

      $return &= !empty($address->getFirstline());
      $return &= !empty($address->getPostalCode());
      $return &= !empty($address->getCity());

      return $return;
    }

    private function checkInvoiceLegalNotice(Business $business) {
      $invoiceLegalNoticeRequirement = array(
                 'NonProfit' => array(
                   'siret' => true,
                 ),
                 'AE' => array(
                   'siret' => true,
                 ),
                 'EI' => array(
                   'siret' => true,
                   'ape' => true,
                   'tvaNumber' => true
                 ),
                 'EIRLnoTVA' => array(
                   'siret' => true
                 ),
                 'EIRL' => array(
                   'siret' => true,
                   'ape' => true,
                   'tvaNumber' => true
                 ),
                 'Micro' => array(
                   'siret' => true
                 ),
                 'SARLvariable' => array(
                   'siret' => true,
                   'rcs' => true,
                   'ape' => true,
                   'tvaNumber' => true
                 ),
                 'SARLEURL' => array(
                   'shareCapital' => true,
                   'siret' => true,
                   'rcs' => true,
                   'ape' => true,
                   'tvaNumber' => true
                 ),
                 'SAS' => array(
                   'shareCapital' => true,
                   'siret' => true,
                   'rcs' => true,
                   'ape' => true,
                   'tvaNumber' => true
                 ),
                 'SA' => array(
                   'siret' => true,
                   'rcs' => true,
                   'ape' => true,
                   'tvaNumber' => true
                 )
               );

      $legalStatus = $business->getLegalStatus();

      if (!array_key_exists($legalStatus , $invoiceLegalNoticeRequirement )) {
        throw new \Exception("Legal Status Requirement not set", 1);
      }
      $requirements = $invoiceLegalNoticeRequirement[$legalStatus];

      $return = true;
      foreach($requirements as $key => $requirement) {
        switch($key) {
            case 'shareCapital' :
                  $return &= is_float($business->getShareCapital()) && $business->getShareCapital() > 0;
            break;
            case 'siret' :
                  $return &= !empty($business->getSiret());
            break;
            case 'rcs' :
                  $return &= !empty($business->getRCSNumber());
            break;
            case 'ape' :
                  $return &= !empty($business->getAPECode());
            break;
            case 'tvaNumber' :
                  $return &= !empty($business->getVatNumber());
                  $return &= is_float($business->getVatRate()) && $business->getVatRate() > 0;
            break;
            default:
                throw new \Exception("Bad Requirement settings", 1);
            break;
        }
      }

      return $return;
    }
}
