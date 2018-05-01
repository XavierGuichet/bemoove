<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;

use Bemoove\AppBundle\Entity\Workout;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

use Doctrine\ORM\EntityManager;
use Bemoove\AppBundle\Entity\Account;
use Bemoove\AppBundle\Entity\RegistrationToken;
use Bemoove\AppBundle\Entity\BankAccount;
use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Person;
use Bemoove\AppBundle\Entity\Place\Address;

use Bemoove\AppBundle\Services\MyMail;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class RegistrationSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory,  MyMail $mailer)
    {
        $this->encoderFactory = $encoderFactory;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                                  ['prepareNewAccount', EventPriorities::PRE_WRITE]
        ]
        ];
    }

    public function prepareNewAccount(GetResponseForControllerResultEvent $event)
    {
        $account = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$account instanceof Account || Request::METHOD_POST !== $method) {
            return;
        }

        if ($account->getIsCoach() === true) {
            $account->setRoles(array("ROLE_PARTNER"));
            $this->mailer->sendWelcomeCoachEmail($account->getEmail());
        } else {
            $this->mailer->sendWelcomeCoachEmail($account->getEmail());
        }

        $this->encodePWd($account);
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
}
