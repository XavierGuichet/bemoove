<?php

// src/AppBundle/Action/GetMyBankAccount.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\Place\Address;
use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Person;
use Bemoove\AppBundle\Entity\BankAccount;


use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMyBankAccount
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
     *     name="getMyBankAccount",
     *     path="/getMyBankAccount",
     *     defaults={"_api_resource_class"=BankAccount::class, "_api_collection_operation_name"="getMyBankAccount"}
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

        $BankAccountRepo = $this->em->getRepository('BemooveAppBundle:BankAccount');
        $bankAccount = $BankAccountRepo->findOneByBusiness($business);

        if($bankAccount === null) {
            $bankAccount = new BankAccount();
            $bankAccount->setBusiness($business);
            $bankAccount->setAddress(new Address());
            $this->em->persist($bankAccount);
            $this->em->flush();
        }

        return $bankAccount;
    }
}
