<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use OrderBundle\Entity\Order;
use Bemoove\AppBundle\Entity\Reservation;
use Bemoove\AppBundle\Entity\WorkoutInstance;

final class RemoveAvaibleTickets implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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

        if (!$order instanceof Order || Request::METHOD_POST !== $method) {
            return;
        }

        $reservation = $order->getReservation();

        $workoutInstance = $reservation->getWorkoutInstance();

        $workoutInstance->addTicketBooked($reservation->getNbBooking());

        $this->em->persist($workoutInstance);
        $this->em->flush();
    }
}
