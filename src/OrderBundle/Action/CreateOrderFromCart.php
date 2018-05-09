<?php
// src/AppBundle/Action/MakeOrder.php

namespace OrderBundle\Action;

use OrderBundle\Entity\Cart;
use OrderBundle\Entity\Order;
use OrderBundle\Entity\OrderHistory;
use OrderBundle\Entity\OrderStatus;
use OrderBundle\Entity\Payment;
use Bemoove\AppBundle\Entity\Reservation;
use Bemoove\AppBundle\Entity\ReservationState;

use OrderBundle\Services\OrderManager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;


use Bemoove\AppBundle\Services\MangoPay\ApiService as MangoPayApiService;
use \Bemoove\AppBundle\Services\MangoPay\ValidationService as MangoPayValidationService;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateOrderFromCart
{
    private $securityTokenStorage;
    private $em;
    private $mangoPayService;
    private $mangoPayValidation;
    private $orderManager;

    public function __construct(TokenStorageInterface $securityTokenStorage, EntityManagerInterface $em, MangoPayValidationService $mangoPayValidation, MangoPayApiService $mangoPayService, OrderManager $orderManager)
    {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->em = $em;

        $this->mangoPayService = $mangoPayService;
        $this->mangoPayValidation = $mangoPayValidation;
        $this->orderManager = $orderManager;
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

      // Prepare les reservations
      // Ce qui va supprimer les places necessaire de la session de la séance
      $reservations = $this->createReservationsFromCart($cart);

      // Ajout des produits à la commande
      $order = $this->prepareOrderFromCart($cart);
      foreach($reservations as $reservation) {
        $order->addReservation($reservation);
        $reservation->setOrder($order);
        $this->em->persist($reservation);
      }

      // Calcul du cout de la commande
      $order->updateOrderTotalAmounts();
      $this->em->persist($order);
      $this->em->flush();

      // This part should already be done.
      // It should only require a quick checking on second part (else)
      $person = $account->getPerson();
      if(!$person->getMangoPayId()) {
        if($this->mangoPayValidation->checkNaturalUserIsReady($person)) {
          $mangoPayUser = $this->mangoPayService->createNaturalUser($person);
          $person->setMangoPayId($mangoPayUser->Id);
          $this->em->persist($person);
          $this->em->flush();
          $mangoPayUserWallet = $this->mangoPayService->createWallet($mangoPayUser);
        } else {
          throw new \Exception("Natural User Not ready", 1);
        }
      } else {
        $mangoPayUser = $this->mangoPayService->updateNaturalUser($person);
        $mangoPayUserWallet = $this->mangoPayService->getWalletOfUser($mangoPayUser);
      }
      // END

      $payIn = $this->mangoPayService->createCardWebPayIn($order, $mangoPayUserWallet, $mangoPayUser);
      if($payIn->Status !== "CREATED") {
        throw new \Exception("PayIn creation failed", 1);
      }
      $payment = new Payment();
      $payment->setMangoIdTransaction($payIn->Id);
      $payment->setTransactionRedirectUrl($payIn->ExecutionDetails->RedirectURL);
      $payment->setStatus($payIn->Status);
      $this->em->persist($payment);
      $order->setPayment($payment);
      $this->em->flush();

      return $order;
    }

    private function prepareOrderFromCart(Cart $cart) {
      $order = new Order();
      $order->setCart($cart);

      $seller = $cart->getSeller();
      $order->setSeller($seller);
      $order->setTaxRate($seller->getVatRate());

      $customer = $cart->getMember();
      $order->setCustomer($customer);

      $order = $this->orderManager->setNewOrderState($order, OrderStatus::WAITINGPAYMENT);

      return $order;
    }

    private function createReservationsFromCart(Cart $cart) {
      $reservations = array();
      foreach($cart->getProducts() as $cartProduct) {
        $workoutInstance = $cartProduct->getProduct();
        $quantity = $cartProduct->getQuantity();
        // Check ticket are always available
        if($workoutInstance->getNbTicketAvailable() < $quantity) {
          throw new \Exception("Not enough Ticket avaible for this session", 1);
        }
        $workoutInstance->addTicketBooked($quantity);
        $this->em->persist($workoutInstance);

        $reservationStateRepository =  $this->em->getRepository('BemooveAppBundle:ReservationState');
        $newReservationState = $reservationStateRepository->find(ReservationState::PENDING);

        $reservation = new Reservation();
        $reservation->setState($newReservationState);
        $reservation->setPerson($cart->getMember());
        $reservation->setWorkoutInstance($workoutInstance);
        $reservation->setNbBooking($cartProduct->getQuantity());
        $reservation->setUnitPriceTaxIncl($workoutInstance->getWorkout()->getPrice());
        $this->em->persist($reservation);
        array_push($reservations, $reservation);
      }

      return $reservations;
    }
}
