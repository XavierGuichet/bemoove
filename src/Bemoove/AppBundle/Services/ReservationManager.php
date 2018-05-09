<?php

namespace Bemoove\AppBundle\Services;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Assetic\Exception\Exception;

use Bemoove\AppBundle\Entity\Reservation;
use Bemoove\AppBundle\Entity\ReservationState;

use Doctrine\ORM\EntityManagerInterface;
use OrderBundle\Services\OrderManager;

class ReservationManager {
    private $em;
    private $orderManager;

    public function __construct(EntityManagerInterface $em, OrderManager $orderManager) {
        $this->em = $em;
        $this->orderManager = $orderManager;
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function changeReservationState(Reservation $reservation, $id_reservation_state)
    {
      $oldstate = $reservation->getState();
      // already in this state, do nothing
      if($oldstate->getId() === $id_reservation_state) {
        return;
      }

      $reservationStateRepository =  $this->em->getRepository('BemooveAppBundle:ReservationState');
      $newReservationState = $reservationStateRepository->find($id_reservation_state);

      $reservation->setState($newReservationState);

      if($id_reservation_state == ReservationState::CANCELLED) {
        $workoutInstance = $reservation->getWorkoutInstance();
        $workoutInstance->removeTicketBooked($reservation->getNbBooking());
        $this->em->persist($workoutInstance);
      }

      $this->em->persist($reservation);
      $this->em->flush();
    }


    /**
     * check a reservation can be cancelled
     *
     * @param Reservation
     * @return boolval
     */
    public function checkReservationCancellable(Reservation $reservation) {
      $now = new \DateTime();
      $startDateInFuture = ($reservation->getWorkoutInstance()->getStartdate()->getTimestamp() - $now->getTimestamp()) > 0;

      $reservationNotCancelled = $reservation->getState()->getId() != ReservationState::CANCELLED;

      $cancellable = $startDateInFuture && $reservationNotCancelled;
      return $cancellable;
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return return type
     */
    public function cancelReservation($reservation)
    {
      $this->changeReservationState($reservation, ReservationState::CANCELLED);

      if($order = $reservation->getOrder()) {
        $this->orderManager->cancelOrder($order);
      }

      return null;
    }
}
