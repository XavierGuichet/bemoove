<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Bemoove\AppBundle\Entity\Workout;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\ORM\EntityManager;
use Bemoove\AppBundle\Entity\Account;
use Bemoove\AppBundle\Entity\RegistrationToken;
use Bemoove\AppBundle\Entity\BankAccount;
use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Person;
use Bemoove\AppBundle\Entity\Profile;
use Bemoove\AppBundle\Entity\Place\Address;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class RegistrationSubscriber implements EventSubscriberInterface
{
    private $encoderFactory;
    private $em;

    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManager $em)
    {
        $this->encoderFactory = $encoderFactory;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                                  ['prepareNewAccount', EventPriorities::PRE_WRITE],
                                  ['createEmptyPartnerData', EventPriorities::POST_WRITE],
        ]
        ];
    }

    /*
     * After a partner account is created
     * We create basic linked Entities
     * So we create these :
     * - Coordonnees de l'entreprise
     * - Representant legal
     * - Addresse de facturation
     * - Taux de TVA
     * - Forme Juridique de la societé
     * - Business Information
     * - Signature du mandat de facturation
     * - profil
     * - Partie accounting
     */

     /*
     * Bank_account + adresse
     * Businness
     *
     */

    public function prepareNewAccount(GetResponseForControllerResultEvent $event)
    {
        $account = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();


        if (!$account instanceof Account || Request::METHOD_POST !== $method) {
            return;
        }

        // when a creationToken is present, the form submission should come from the specific page for partner registration
        // If token is valid and unused, a partner account will be created
        // else the registration will fail
        $token = $account->getCreationToken();
        if ($token !== null) {
            if ( $this->isCreationTokenValid($token) ) {
                $account->setRoles(array("ROLE_PARTNER"));
                $account->setCreationToken( $token );
            }
            else {
                var_dump('Token invalid');
                die();
            }
        }

        $this->encodePWd($account);
    }

    public function createEmptyPartnerData(GetResponseForControllerResultEvent $event) {
        $account = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$account instanceof Account || Request::METHOD_POST !== $method) {
            return;
        }

        $business = new Business();
        $business->setOwner($account);
        $businessAddress = new Address();
        $businessAddress->setOwner($account);
        $business->setAddress($businessAddress);
        $businessLegalRepresentative = new Person();
        $business->setLegalRepresentative($businessLegalRepresentative);
        $this->em->persist($business);


        $bankAccount = new BankAccount();
        $bankAccount->setOwner($account);
        $bankAccountAddress = new Address();
        $bankAccountAddress->setOwner($account);
        $bankAccount->setAddress($bankAccountAddress);
        $this->em->persist($bankAccount);

        $profile = new Profile();
        $profile->setOwner($account);
        $profileAddress = new Address();
        $profile->setAddress($profileAddress);
        $this->em->persist($profile);

        $this->em->flush();

    }

    public function encodePWd(Account $account)
    {
        $plainPassword = $account->getPassword();
        $encoder = $this->encoderFactory->getEncoder($account);

        if ($encoder instanceof BCryptPasswordEncoder) {
            $account->setSalt(null);
        }
        else {
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $account->setSalt($salt);
        }

        $hashedPassword = $encoder->encodePassword($plainPassword, $account->getSalt());

        $account->setPassword($hashedPassword);
        $account->eraseCredentials();
    }

    private function isCreationTokenValid($token)
    {
        $tokenRepo = $this->em->getRepository('BemooveAppBundle:RegistrationToken');
        $registrationToken = $tokenRepo->findOneByToken($token);
        // var_dump($token);
        // var_dump("post findONy");
        // var_dump($registrationToken);
        if ( $registrationToken ) {
            // var_dump($registrationToken);
            if ($registrationToken->getDateUsed() !== NULL) {
                return false;
            }
            $registrationToken->setDateUsed(new \DateTime());
            $this->em->persist($registrationToken);
            $this->em->flush();
            return true;
        }
        return false;
    }
}
