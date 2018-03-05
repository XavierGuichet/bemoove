<?php
// src/AppBundle/Action/MakeOrder.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Order;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MakeOrder
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
     *     name="makeOrder",
     *     path="/makeOrder",
     *     defaults={"_api_resource_class"=Order::class, "_api_collection_operation_name"="makeOrder"}
     * )
     * @Method("POST")
     */
    public function __invoke($data)
    {

    }
}
