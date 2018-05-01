<?php
// src/AppBundle/DataFixtures/ORM/LoadQuestionnaire.php
namespace OrderBundle\DataFixtures\ORM;


use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OrderBundle\Entity\OrderStatus;

class LoadOrderState implements ORMFixtureInterface
{
    public function load(ObjectManager $em)
    {

    $orderStates = array(
      OrderStatus::WAITINGPAYMENT => array(
        'name' => 'En attente de paiement',
        'description' => ''
      ),
      OrderStatus::PAYMENTACCEPTED => array(
        'name' => 'Paiement accepté',
        'description' => ''
      ),
      OrderStatus::DONE => array(
        'name' => 'Cours réalisé',
        'description' => ''
      ),
      OrderStatus::PAYMENTERROR => array(
        'name' => 'Erreur de paiement',
        'description' => ''
      ),
      OrderStatus::CANCELED => array(
        'name' => 'Annulée',
        'description' => ''
      ),
      OrderStatus::WAINTINGREFUND => array(
        'name' => 'En attente de remboursement',
        'description' => ''
      ),
      OrderStatus::REFUNDED => array(
        'name' => 'Remboursé',
        'description' => ''
      )
    );

    foreach ($orderStates as $id => $orderState) {
        $ObjOrderState = new OrderStatus();
        $ObjOrderState->setId($id);
        $ObjOrderState->setName($orderState['name']);
        $ObjOrderState->setDescription($orderState['description']);
        $em->persist($ObjOrderState);
    }

    $em->flush();
    }
}
