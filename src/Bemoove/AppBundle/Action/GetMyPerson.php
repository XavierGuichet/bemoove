<?php

// src/AppBundle/Action/BookSpecial.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Person;
use Bemoove\AppBundle\Entity\Place\Address;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMyPerson
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
     *     name="getMyPerson",
     *     path="/getMyPerson",
     *     defaults={"_api_resource_class"=Person::class, "_api_collection_operation_name"="getMyPerson"}
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

        if($person) {
            if(!$person->getAddress()){
                $address = new Address();
                $this->em->persist($address);
                $person->setAddress($address);
                $this->em->persist($person);
                $this->em->flush();
            }
            return $person;
        }

        $address = new Address();
        $this->em->persist($address);
        $person = new Person();
        $person->setAddress($address);
        $this->em->persist($person);
        $account->setPerson($person);
        $this->em->persist($account);
        $this->em->flush();

        return $account->getPerson();
    }
}
