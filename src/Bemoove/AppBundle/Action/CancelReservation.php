<?php

namespace Bemoove\AppBundle\Action;

use OrderBundle\Entity\Order;
use OrderBundle\Entity\OrderHistory;
use OrderBundle\Entity\OrderStatus;
use OrderBundle\Entity\Payment;
use Bemoove\AppBundle\Entity\Reservation;
use Bemoove\AppBundle\Entity\ReservationState;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Bemoove\AppBundle\Services\ReservationManager;

class CancelReservation {
  private $reservationManager;

  public function __construct(ReservationManager $reservationManager)
  {
      $this->reservationManager = $reservationManager;
  }

  /**
   * @Route(
   *     name="cancelReservation",
   *     path="/cancelReservation/{id}",
   *     defaults={"_api_resource_class"=Reservation::class, "_api_item_operation_name"="cancelReservation"}
   * )
   * @Method("GET")
   */
  public function __invoke($data)
  {
    $reservation = $data;
    
    // Verifie que les conditions d'annulation sont rempli
    $reservationCancellable = $this->reservationManager->checkReservationCancellable($reservation);
    if(!$reservationCancellable) {
      throw new \Exception("Reservation can't be cancelled", 1);
    }
    $this->reservationManager->cancelReservation($reservation);


    return $reservation;
  }


}
