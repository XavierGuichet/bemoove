<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use Bemoove\AppBundle\Entity\Order;
use Bemoove\AppBundle\Entity\Reservation;
use Bemoove\AppBundle\Entity\WorkoutInstance;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Bemoove\AppBundle\Services\MyMail;
use Bemoove\AppBundle\Services\MangoPayService;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class RemoveAvaibleTickets implements EventSubscriberInterface
{
    private $mailer;
    private $encoderFactory;
    private $em;
    private $jwtManager;
    private $mangopay;

    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManagerInterface $em, JWTTokenManagerInterface $jwtManager, MyMail $mailer, MangoPayService $mangopay)
    {
        $this->mangopay = $mangopay;
        $this->encoderFactory = $encoderFactory;
        $this->em = $em;
        $this->jwtManager = $jwtManager;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                                  ['removeAvaibleTickets', EventPriorities::POST_WRITE]
        ]
        ];
    }

    public function removeAvaibleTickets(GetResponseForControllerResultEvent $event)
    {
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        dump($order);
        dump($method);

        if (!$order instanceof Order || Request::METHOD_POST !== $method) {
            return;
        }

        $reservation = $order->getReservation();

        $workoutInstance = $reservation->getWorkoutInstance();

        $workoutInstance->addTicketBooked($reservation->getNbBooking());

        dump($workoutInstance);

        $this->em->persist($workoutInstance);
        $this->em->flush();
    }
}
