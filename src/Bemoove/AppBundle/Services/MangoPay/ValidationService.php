<?php

namespace Bemoove\AppBundle\Services\MangoPay;


use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Person;
use OrderBundle\Entity\Order;

class ValidationService
{
  /**
   * Check a person have all needed informations in order to create a MangoPay NaturalUser
   */
  public function checkNaturalUserIsReady(Person $person) {
    if(!$person->getFirstname()) {
      return false;
    }
    if(!$person->getLastname()) {
      return false;
    }
    if(!$person->getBirthdate()->getTimestamp()) {
      return false;
    }
    if(!$person->getNationality()) {
      return false;
    }
    if(!$person->getCountryOfResidence()) {
      return false;
    }
    if(!$person->getEmail()) {
      return false;
    }
    return true;
  }


  /**
   * Check a business have all needed informations in order to create a MangoPay LegalUser
   */
  public function checkLegalUserIsReady(Business $business) {
      if(!$business->getLegalName()) {
        return false;
      }
      if(!$business->getMail()) {
        return false;
      }
      $legalRepresentative = $business->getLegalRepresentative();
      if(!$legalRepresentative) {
        return false;
      }
      if(!$legalRepresentative->getLastname()) {
        return false;
      }
      if(!$legalRepresentative->getFirstname()) {
        return false;
      }
      if(!$legalRepresentative->getNationality()) {
        return false;
      }
      if(!$legalRepresentative->getCountryOfResidence()) {
        return false;
      }
      if(!$legalRepresentative->getBirthdate()) {
        return false;
      }
      return true;
  }
}
