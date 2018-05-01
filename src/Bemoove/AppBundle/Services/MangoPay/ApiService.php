<?php

namespace Bemoove\AppBundle\Services\MangoPay;


use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Person;
use OrderBundle\Entity\Order;

use MangoPay;

class ApiService
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

     public function getNatural($mangoPayId) {
       return $this->mangoPayApi->Users->GetNatural($mangoPayId);
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
        $mangoUser->Email = $business->getMail();
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
          $mangoUser = $this->mangoPayApi->Users->GetLegal($mangoPayId);

          $mangoUser->Name = $business->getLegalName();
          $mangoUser->Email = $business->getMail();
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

       public function createWallet(\MangoPay\User $mangoUser) {
         $wallet = new \MangoPay\Wallet();
         $wallet->Owners = array($mangoUser->Id);
         $wallet->Description = 'Sporty';
         $wallet->Currency = 'EUR';

         $mangoWallet = $this->mangoPayApi->Wallets->Create($wallet);

         return $mangoWallet;
       }

       public function getWalletOfUser(\MangoPay\User $mangoUser) {
         $mangoPayUserWallets = $this->mangoPayApi->Users->getWallets($mangoUser->Id);

         if(count($mangoPayUserWallets) === 0) {
           return $this->createWallet($mangoUser);
         }

         return $mangoPayUserWallets[0];
       }

       public function createCardWebPayIn(Order $order, $creditedWallet, $mangoUser) {
         $webPayIn = new \MangoPay\PayIn();

         $webPayIn->Tag = "Order ".$order->getOrderNumber();
         //Who
         $webPayIn->AuthorId = $mangoUser->Id;
         $webPayIn->CreditedWalletId = $creditedWallet->Id;

         //How Much
         $webPayIn->DebitedFunds = new \MangoPay\Money();
         $webPayIn->DebitedFunds->Currency = "EUR";
         $webPayIn->DebitedFunds->Amount = (int) $order->getTotalAmountTaxIncl() * 100;

         //How much take Bemoove
         $webPayIn->Fees = new \MangoPay\Money();
         $webPayIn->Fees->Currency = "EUR";
         $webPayIn->Fees->Amount = 0;

         //Where to go after
         $webPayIn->ReturnURL = "http://localhost:3000/order/checkout/step/validation/".$order->getId();



          $webPayIn->CardType = "CB_VISA_MASTERCARD";
          $webPayIn->Culture = "EN";

          $webPayIn->PaymentType = "CARD";
          $webPayIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
          $webPayIn->PaymentDetails->CardType = "CB_VISA_MASTERCARD";

          $webPayIn->ExecutionType = "WEB";
          $webPayIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsWeb();
          $webPayIn->ExecutionDetails->ReturnURL = "http://localhost:3000/order/checkout/step/validation/".$order->getId();
          $webPayIn->ExecutionDetails->CultureCode = "EN";

          $result = $this->mangoPayApi->PayIns->Create($webPayIn);

          return $result;
       }

       public function getPayIn($transaction_id) {
         $payIn = $this->mangoPayApi->PayIns->Get($transaction_id);

         return $payIn;
       }
}
