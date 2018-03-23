<?php

namespace Bemoove\AppBundle\Services;


use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Person;

use MangoPay;

class MangoPayService
{
    private $mangoPayApi;

    public function __construct(string $mangopay_tmp_path)
    {
        $this->mangoPayApi = new MangoPay\MangoPayApi();
        $this->mangoPayApi->Config->ClientId = 'bemoove';
        $this->mangoPayApi->Config->ClientPassword = 'XS0gE5Lz7TZKXMbLS77GSNdut5heXBVbvWu4G2R9oBVVdUBoYn';
        $this->mangoPayApi->Config->TemporaryFolder = $mangopay_tmp_path;
        $this->mangoPayApi->Config->BaseUrl = 'https://api.sandbox.mangopay.com';
    }

    /**
     * Create Natural User
     * @return MangoPayUser $mangoUser
     */
     public function createNaturalUser(Person $person) {
        if($person->getMangoPayId()) {
          throw new \Exception("NaturalUser already created", 1);
        }
        $mangoUser = new \MangoPay\UserNatural();
        $mangoUser->FirstName = $person->getFirstname();
        $mangoUser->LastName = $person->getLastname();
        $mangoUser->Birthday = $person->getBirthdate()->getTimestamp();
        $mangoUser->Nationality = $person->getNationality();
        $mangoUser->CountryOfResidence = $person->getCountryOfResidence();
        $mangoUser->Email = $person->getEmail();

        $mangoUser = $this->mangoPayApi->Users->Create($mangoUser);

        return $mangoUser;
     }

    /**
     * Update Natural User
     * @return MangoPayUser $mangoUser
     */
     public function updateNaturalUser(Person $person) {
        if(!$mangoPayId = $person->getMangoPayId()) {
          throw new \Exception("NaturalUser doesn't exists", 1);
        }
        $mangoUser = $this->mangoPayApi->Users->GetNatural($mangoPayId);

        $mangoUser->FirstName = $person->getFirstname();
        $mangoUser->LastName = $person->getLastname();
        $mangoUser->Birthday = $person->getBirthdate()->getTimestamp();
        $mangoUser->Nationality = $person->getNationality();
        $mangoUser->CountryOfResidence = $person->getCountryOfResidence();
        $mangoUser->Email = $person->getEmail();

        $mangoUser = $this->mangoPayApi->Users->Update($mangoUser);

        return $mangoUser;
     }

     /**
      * Create Legal User
      * @return MangoPayUser $mangoUser
      */
      public function createLegalUser(Business $business) {
        if($business->getMangoPayId()) {
          throw new \Exception("LegalUser already created", 1);
        }
        $mangoUser = new \MangoPay\UserLegal();

        $mangoUser->Name = $business->getLegalName();
        $mangoUser->Email = $business->getEmail();
        $mangoUser->LegalPersonType = 'BUSINESS';

        $legalRepresentative = $business->getLegalRepresentative();
        $mangoUser->LegalRepresentativeBirthday = $legalRepresentative->getBirthdate()->getTimestamp();
        $mangoUser->LegalRepresentativeCountryOfResidence = $legalRepresentative->getCountryOfResidence();
        $mangoUser->LegalRepresentativeNationality = $legalRepresentative->getNationality();
        $mangoUser->LegalRepresentativeFirstName = $legalRepresentative->getFirstname();
        $mangoUser->LegalRepresentativeLastName = $legalRepresentative->getLastname();

        $mangoUser = $this->mangoPayApi->Users->Create($mangoUser);

        return $mangoUser;
      }

      /**
       * Update Legal User
       * @return MangoPayUser $mangoUser
       */
       public function updateLegalUser(Business $business) {
          if(!$mangoPayId = $business->getMangoPayId()) {
            throw new \Exception("LegalUser doesn't exists", 1);
          }
          $mangoUser = $this->mangoPayApi->GetLegal($mangoPayId);

          $mangoUser->Name = $business->getLegalName();
          $mangoUser->Email = $business->getEmail();
          $mangoUser->LegalPersonType = 'BUSINESS';

          $legalRepresentative = $business->getLegalRepresentative();
          $mangoUser->LegalRepresentativeBirthday = $legalRepresentative->getBirthdate()->getTimestamp();
          $mangoUser->LegalRepresentativeCountryOfResidence = $legalRepresentative->getCountryOfResidence();
          $mangoUser->LegalRepresentativeNationality = $legalRepresentative->getNationality();
          $mangoUser->LegalRepresentativeFirstName = $legalRepresentative->getFirstname();
          $mangoUser->LegalRepresentativeLastName = $legalRepresentative->getLastname();

          $mangoUser = $this->mangoPayApi->Users->Update($mangoUser);

          return $mangoUser;
       }
}
