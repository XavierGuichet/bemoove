<?php

// src/AppBundle/Action/BookSpecial.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Reservation;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMyReservations
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
     *     name="getMyReservations",
     *     path="/getMyReservations",
     *     defaults={"_api_resource_class"=Reservation::class, "_api_collection_operation_name"="getMyReservations"}
     * )
     * @Method("GET")
     */
    public function __invoke($data) // API Platform retrieves the PHP entity using the data provider then (for POST and
                                    // PUT method) deserializes user data in it. Then passes it to the action. Here $data
                                    // is an instance of Book having the given ID. By convention, the action's parameter
                                    // must be called $data.
    {
        $account = $this->securityTokenStorage->getToken()->getUser();

        $person = $account->getPerson();

        $reservationRepo = $this->em->getRepository('BemooveAppBundle:Reservation');

        $reservations = $reservationRepo->findByPerson($person);

        return $reservations;
    }
}
