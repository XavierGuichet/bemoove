<?php

// src/AppBundle/Action/BookSpecial.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Person;
use Bemoove\AppBundle\Entity\Place\Address;


use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMyBusiness
{
    private $securityTokenStorage;
    private $em;

    public function __construct(TokenStorageInterface $securityTokenStorage, EntityManager $em)
    {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->em = $em;
    }

    /**
     * @Route(
     *     name="getMyBusiness",
     *     path="/getMyBusiness",
     *     defaults={"_api_resource_class"=Business::class, "_api_collection_operation_name"="getMyBusiness"}
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

        if($business === null) {
            $business = new Business();
            $business->setOwner($account);
            $business->setLegalRepresentative(new Person());
            $business->setAddress(new Address());
            $this->em->persist($business);
            $this->em->flush();
        }

        return $business;
    }
}
