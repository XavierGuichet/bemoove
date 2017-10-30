<?php

// src/AppBundle/Action/BookSpecial.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Place\Address;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMyWorkoutAddress
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
     *     name="getMyWorkoutAddress",
     *     path="/getMyWorkoutAddress",
     *     defaults={"_api_resource_class"=Address::class, "_api_collection_operation_name"="getMyWorkoutAddress"}
     * )
     * @Method("GET")
     */
    public function __invoke($data)
    {
        $account = $this->securityTokenStorage->getToken()->getUser();

        $addressRepo = $this->em->getRepository('BemooveAppBundle:Place\Address');

        $addresses = $addressRepo->findBy(
                            array( 'owner' => $account,
                                   'isWorkoutLocation' => true
                               )
                            );

        return $addresses;
    }
}
