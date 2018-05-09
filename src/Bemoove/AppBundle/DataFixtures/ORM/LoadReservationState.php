<?php
namespace Bemoove\AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Bemoove\AppBundle\Entity\ReservationState;

class LoadReservationState implements ORMFixtureInterface
{
    public function load(ObjectManager $em)
    {

    $reservationStates = array(
      ReservationState::VALID => array(
        'name' => 'Valide',
        'description' => ''
      ),
      ReservationState::PENDING => array(
        'name' => 'En attente',
        'description' => ''
      ),
      ReservationState::CANCELLED => array(
        'name' => 'Annulée',
        'description' => ''
      )
    );

    foreach ($reservationStates as $id => $reservationState) {
        $objReservationState = new ReservationState();
        $objReservationState->setId($id);
        $objReservationState->setName($reservationState['name']);
        $objReservationState->setDescription($reservationState['description']);
        $em->persist($objReservationState);
    }

    $em->flush();
    }
}
