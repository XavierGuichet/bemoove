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
use Bemoove\AppBundle\Entity\User;
use Bemoove\AppBundle\Entity\MailChangeRequest;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class MailChangeRequestSubscriber implements EventSubscriberInterface
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['prepareNewMailChangeRequest', EventPriorities::PRE_WRITE]]
        ];
    }

    public function prepareNewMailChangeRequest(GetResponseForControllerResultEvent $event)
    {
        $mailChangeRequest = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();


        if (!$mailChangeRequest instanceof MailChangeRequest || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->securityTokenStorage->getToken()->getUser();

        $mailChangeRequest->setUser( $user );
        $mailChangeRequest->setToken( rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=') );
    }

    // public function encodePWd(User $user)
    // {
    //     $plainPassword = $user->getPassword();
    //     $encoder = $this->encoderFactory->getEncoder($user);
    //
    //     if ($encoder instanceof BCryptPasswordEncoder) {
    //         $user->setSalt(null);
    //     }
    //     else {
    //         $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
    //         $user->setSalt($salt);
    //     }
    //
    //     $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
    //
    //     $user->setPassword($hashedPassword);
    //     $user->eraseCredentials();
    // }
}
