<?php

namespace OrderBundle\Services;

use OrderBundle\Entity\Order;
use OrderBundle\Entity\OrderHistory;
use OrderBundle\Entity\OrderStatus;
use Bemoove\AppBundle\Services\MangoPay\ApiService as MangoPayApiService;

use Doctrine\ORM\EntityManagerInterface;

class OrderManager {
  private $em;
  private $mangoPayService;

  public function __construct(EntityManagerInterface $em, MangoPayApiService $mangoPayService)
  {
    $this->em = $em;
    $this->mangoPayService = $mangoPayService;
  }

  /**
   * undocumented function summary
   *
   * Undocumented function long description
   *
   * @param type var Description
   * @return return Order
   */
  public function setNewOrderState(Order $order, $id_order_state)
  {
    $orderStateRepository =  $this->em->getRepository('OrderBundle:OrderStatus');
    $orderStatus = $orderStateRepository->find($id_order_state);
    if(!$orderStatus) {
      throw new \Exception("Order State not found".$id_order_state);
    }

    $orderHistory = new OrderHistory();
    $orderHistory->setOrderState($orderStatus);
    $orderHistory->setOrder($order);
    $this->em->persist($orderHistory);

    $order->addStatusHistory($orderHistory);
    $this->em->persist($order);

    return $order;
  }

  /**
   * undocumented function summary
   *
   * Undocumented function long description
   *
   * @param type var Description
   * @return return type
   */
  public function cancelOrder($order)
  {
    if($this->checkOrderIsRefundable($order)) {
      $this->setNewOrderState($order, OrderStatus::WAINTINGREFUND);
      $mangoIdTransaction = $order->getPayment()->getMangoIdTransaction();
      $mangoIdUser = $order->getCustomer()->getMangoPayId();
      if($this->mangoPayService->createPayInRefundFor($mangoIdTransaction, $mangoIdUser) === "SUCCEDED") {
        $this->setNewOrderState($order, OrderStatus::REFUNDED);
      }
    } else {
      $this->setNewOrderState($order, OrderStatus::CANCELED);
    }

    return null;
  }

  public function checkOrderIsRefundable($order) {
    // order was paid
    if($order->getPayment()->getStatus() === 'SUCCEEDED') {
      return true;
    }
    return false;
  }


}
