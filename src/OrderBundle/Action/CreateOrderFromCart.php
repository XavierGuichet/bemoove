<?php
// src/AppBundle/Action/MakeOrder.php

namespace OrderBundle\Action;

use OrderBundle\Entity\Cart;
use OrderBundle\Entity\Order;
use OrderBundle\Entity\OrderHistory;
use OrderBundle\Entity\OrderStatus;
use OrderBundle\Entity\Payment;
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
    private $mangoPayService;
    private $mangoPayValidation;

    public function __construct(TokenStorageInterface $securityTokenStorage, EntityManagerInterface $em, \Bemoove\AppBundle\Services\MangoPay\ValidationService $mangoPayValidation, \Bemoove\AppBundle\Services\MangoPay\ApiService $mangoPayService)
    {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->em = $em;

        $this->mangoPayService = $mangoPayService;
        $this->mangoPayValidation = $mangoPayValidation;
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

      $order = $this->prepareOrderFromCart($cart);

      // Ajout des produits à la commande
      $reservation = $this->createReservationsFromCart($cart);
      $order->setReservation($reservation);

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
      $payment = new Payment();
      $payment->setMangoIdTransaction($payIn->Id);
      $payment->setTransactionRedirectUrl($payIn->ExecutionDetails->RedirectURL);
      if($payIn->Status !== "CREATED") {
        throw new \Exception("PayIn creation failed", 1);
      }
      $payment->setStatus($payIn->Status);
      $this->em->persist($payment);
      $order->setPayment($payment);
      $this->em->flush();

      return $order;
    }

    private function prepareOrderFromCart(Cart $cart) {
      $order = new Order();
      $order->setCart($cart);

      $seller = $cart->getWorkoutInstance()->getCoach()->getBusiness();
      $order->setSeller($seller);
      $order->setTaxRate($seller->getVatRate());

      $customer = $cart->getMember();
      $order->setCustomer($customer);

      $orderStateRepository =  $this->em->getRepository('OrderBundle:OrderStatus');
      $newOrderStatus = $orderStateRepository->find(OrderStatus::WAITINGPAYMENT);
      $orderHistory = new OrderHistory();
      $orderHistory->setOrderState($newOrderStatus);
      $orderHistory->setOrder($order);
      $order->addStatusHistory($orderHistory);

      return $order;
    }

    private function createReservationsFromCart(Cart $cart) {
      $reservation = new Reservation();

      $reservation->setPerson($cart->getMember());
      $reservation->setWorkoutInstance($cart->getWorkoutInstance());
      $reservation->setNbBooking($cart->getNbBooking());
      $reservation->setUnitPriceTaxExcl($cart->getWorkoutInstance()->getWorkout()->getPrice());

      $this->em->persist($reservation);
      $this->em->flush();

      return $reservation;
    }
}
