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

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class RegistrationSubscriber implements EventSubscriberInterface
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['prepareNewUser', EventPriorities::PRE_WRITE]]
        ];
    }

    public function prepareNewUser(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();


        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $this->encodePWd($user);
    }

    public function encodePWd(User $user)
    {
        $plainPassword = $user->getPassword();
        $encoder = $this->encoderFactory->getEncoder($user);

        if ($encoder instanceof BCryptPasswordEncoder) {
            $user->setSalt(null);
        }
        else {
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $user->setSalt($salt);
        }

        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());

        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}
