<?php

// src/AppBundle/Action/CheckBillingMandate.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\BillingMandate;
use Bemoove\AppBundle\Entity\Business;
use Bemoove\AppBundle\Entity\Person;
use Bemoove\AppBundle\Entity\Place\Address;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckBillingMandate
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
     *     name="checkBillingMandate",
     *     path="/checkBillingMandate",
     *     defaults={"_api_resource_class"=BillingMandate::class, "_api_collection_operation_name"="checkBillingMandate"}
     * )
     * @Method("GET")
     */
    public function __invoke($data)
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

        $BillingMandateRepo = $this->em->getRepository('BemooveAppBundle:BillingMandate');
        $billingMandate = $BillingMandateRepo->findOneByBusiness($business);

        return $billingMandate;
    }
}
