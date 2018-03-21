<?php
// src/AppBundle/Action/MakeOrder.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Cart;
use Bemoove\AppBundle\Entity\Order;
use Bemoove\AppBundle\Entity\Payment;
use Bemoove\AppBundle\Entity\Reservation;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateOrderFromCart
{
    private $securityTokenStorage;
    private $em;

    public function __construct(TokenStorageInterface $securityTokenStorage, EntityManagerInterface $em)
    {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->em = $em;
    }

    /**
     * @Route(
     *     name="createOrderFromCart",
     *     path="/createOrderFromCart",
     *     defaults={"_api_resource_class"=Order::class, "_api_collection_operation_name"="createOrderFromCart"}
     * )
     * @Method("POST")
     */
    public function __invoke($data)
    {
      $account = $this->securityTokenStorage->getToken()->getUser();

      $cart = $data->getCart();
      $nbBooking = $cart->getNbBooking();
      $workoutInstance = $cart->getWorkoutInstance();
      $workout = $workoutInstance->getWorkout();
      $coach = $workoutInstance->getCoach();
      $business = $coach->getBusiness();

      $data->setCustomer($cart->getMember());
      $data->setSeller($business);


      $price = $workout->getPrice() * $cart->getNbBooking();

      $data->setTaxRate($business->getVatRate());
      $data->setTotalAmountTaxExcl($price);

      $reservation = $this->createReservationFromOrder($data, $workoutInstance, $nbBooking);

      $data->setReservation($reservation);

      // This is tmp
      // $payment = new Payment();
      // $this->em->persist($payment);
      // $this->em->flush();
      $data->setPayment("SUCCEEDED");


      // $order->setStatus(); _DEFAULT_STATUS_; // Not paid ? Unifinished // waiting for paiment

      dump($data);
      return $data;
    }

    private function createReservationFromOrder(Order $order, $workoutInstance, $nbBooking) {
      $reservation = new Reservation();

      $reservation->setPerson($order->getCustomer());
      $reservation->setWorkoutInstance($workoutInstance);
      $reservation->setNbBooking($nbBooking);

      $this->em->persist($reservation);
      $this->em->flush();

      return $reservation;
    }
}
