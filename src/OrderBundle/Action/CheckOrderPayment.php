<?php

namespace OrderBundle\Action;

use OrderBundle\Entity\Order;
use OrderBundle\Entity\OrderStatus;
use OrderBundle\Entity\Payment;
use Bemoove\AppBundle\Entity\Reservation;
use Bemoove\AppBundle\Entity\ReservationState;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use OrderBundle\Services\OrderManager;

use Doctrine\ORM\EntityManagerInterface;

class CheckOrderPayment {
  private $em;
  private $mangoPayService;
  private $orderManager;

  public function __construct(EntityManagerInterface $em, \Bemoove\AppBundle\Services\MangoPay\ApiService $mangoPayService, \OrderBundle\Services\OrderManager $orderManager)
  {
      $this->em = $em;
      $this->mangoPayService = $mangoPayService;
      $this->orderManager = $orderManager;
  }

  /**
   * @Route(
   *     name="checkOrderPayment",
   *     path="/checkOrderPayment/{id}",
   *     defaults={"_api_resource_class"=Order::class, "_api_item_operation_name"="checkOrderPayment"}
   * )
   * @Method("GET")
   */
  public function __invoke($data)
  {
    $order = $data;

    // Verifie le status de la commande
    // Si celui ci indique que la commande est dans un status fixe (par rapport au payment)
    // Ne fait rien de particulier
    $order_state_id = $order->getCurrentStatus()->getOrderState()->getId();

    if($order_state_id === OrderStatus::PAYMENTACCEPTED ||
       $order_state_id === OrderStatus::DONE ||
       $order_state_id === OrderStatus::CANCELED ||
       $order_state_id === OrderStatus::REFUNDED ) {
         return $order;
       }

    // Si la commande est 'en attente de payment' ou en 'erreur de payment'
    // procède à une verification auprès de MangoPay

    // Retrieve MangoPay Transaction of this order
    $payment = $order->getPayment();
    if($payment === null) {
      throw new \Exception("Order doesn't have associated payment", 1);
    }
    $transaction_id = $payment->getMangoIdTransaction();
    $mangoTransaction = $this->mangoPayService->getPayIn($transaction_id);

    $payment->setStatus($mangoTransaction->Status);
    $this->em->persist($payment);

    $reservationStateRepository =  $this->em->getRepository('BemooveAppBundle:ReservationState');
    switch($mangoTransaction->Status) {
      // La transaction est en attente, si ce n'est pas le cas dans l'historique de la commande
      // C'est qu'une nouvelle transaction a du être lancé pour la commande
      // Sinon la commande est dans l'attente que l'utilisateurs paye et nous lui reproposerons le lien de payment
      case 'CREATED':
          if($order_state_id !== OrderStatus::WAITINGPAYMENT) {
              $order = $this->orderManager->setNewOrderState($order, OrderStatus::WAITINGPAYMENT);
              $this->em->persist($order);

              $reservationState = $reservationStateRepository->find(ReservationState::PENDING);
              $reservations = $order->getReservations();
              foreach($reservations as $reservation) {
                $reservation->setState($reservationState);
                $this->em->persist($reservation);
              }
              $this->em->flush();
          }
      break;
      case 'SUCCEEDED':
          $order = $this->orderManager->setNewOrderState($order, OrderStatus::PAYMENTACCEPTED);
          $this->em->persist($order);

          $reservationState = $reservationStateRepository->find(ReservationState::VALID);
          $reservations = $order->getReservations();
          foreach($reservations as $reservation) {
            $reservation->setState($reservationState);
            $this->em->persist($reservation);
          }

          $this->em->flush();
      break;
      case 'FAILED':
          $order = $this->orderManager->setNewOrderState($order, OrderStatus::PAYMENTERROR);
          $this->em->persist($order);

          $reservationState = $reservationStateRepository->find(ReservationState::PENDING);
          $reservations = $order->getReservations();
          foreach($reservations as $reservation) {
            $reservation->setState($reservationState);
            $this->em->persist($reservation);
          }
          $this->em->flush();
      break;
      default:
          throw new \Exception("Unknown transaction status", 1);
      break;
    }

    return $order;
  }
}
