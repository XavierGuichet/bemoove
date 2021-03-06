<?php

// src/AppBundle/Action/BookSpecial.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Coach;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMyCoaches
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
     *     name="getMyCoaches",
     *     path="/getMyCoaches",
     *     defaults={"_api_resource_class"=Coach::class, "_api_collection_operation_name"="getMyCoaches"}
     * )
     * @Method("GET")
     */
    public function __invoke($data) // API Platform retrieves the PHP entity using the data provider then (for POST and
                                    // PUT method) deserializes user data in it. Then passes it to the action. Here $data
                                    // is an instance of Book having the given ID. By convention, the action's parameter
                                    // must be called $data.
    {
        $account = $this->securityTokenStorage->getToken()->getUser();

        $BusinessRepo = $this->em->getRepository('BemooveAppBundle:Business');
        $business = $BusinessRepo->findOneByOwner($account);

        $CoachesRepo = $this->em->getRepository('BemooveAppBundle:Coach');
        $coaches = $CoachesRepo->findByBusiness($business);

        return $coaches;
    }
}
