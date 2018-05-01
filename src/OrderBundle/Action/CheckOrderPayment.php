<?php

namespace OrderBundle\Action;

use OrderBundle\Entity\Order;
use OrderBundle\Entity\OrderHistory;
use OrderBundle\Entity\OrderStatus;
use OrderBundle\Entity\Payment;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

class CheckOrderPayment {
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

    dump($order_state_id);
    // dump($order_state_id);
    // return $order;
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
    dump($mangoTransaction->Status);

    $orderStateRepository =  $this->em->getRepository('OrderBundle:OrderStatus');
    switch($mangoTransaction->Status) {
      // La transaction est en attente, si ce n'est pas le cas dans l'historique de la commande
      // C'est qu'une nouvelle transaction a du être lancé pour la commande
      // Sinon la commande est dans l'attente que l'utilisateurs paye et nous lui reproposerons le lien de payment
      case 'CREATED':
          if($order_state_id !== OrderStatus::WAITINGPAYMENT) {
              $newOrderStatus = $orderStateRepository->find(OrderStatus::WAITINGPAYMENT);
              $orderHistory = new OrderHistory();
              $orderHistory->setOrderState($newOrderStatus);
              $orderHistory->setOrder($order);
              $this->em->persist($orderHistory);
              $order->addStatusHistory($orderHistory);
              $this->em->persist($order);
              $this->em->flush();
          }
      break;
      case 'SUCCEEDED':
          $newOrderStatus = $orderStateRepository->find(OrderStatus::PAYMENTACCEPTED);
          $orderHistory = new OrderHistory();
          $orderHistory->setOrderState($newOrderStatus);
          $orderHistory->setOrder($order);
          $this->em->persist($orderHistory);

          $order->addStatusHistory($orderHistory);
          $this->em->persist($order);

          $this->em->flush();

          //also need to validate reservations
      break;
      case 'FAILED':
          $newOrderStatus = $orderStateRepository->find(OrderStatus::PAYMENTERROR);
          $orderHistory = new OrderHistory();
          $orderHistory->setOrderState($newOrderStatus);
          $orderHistory->setOrder($order);
          $this->em->persist($orderHistory);

          $order->addStatusHistory($orderHistory);
          $this->em->persist($order);

          $this->em->flush();
      break;
      default:
          throw new \Exception("Unknown transaction status", 1);
      break;
    }

    return $order;
  }
}
