<?php

// src/AppBundle/Action/SignBillingMandate.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\BillingMandate;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SignBillingMandate
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
     *     name="signBillingMandate",
     *     path="/signBillingMandate",
     *     defaults={"_api_resource_class"=BillingMandate::class, "_api_collection_operation_name"="signBillingMandate"}
     * )
     * @Method("GET")
     */
    public function __invoke($data)
    {
        $account = $this->securityTokenStorage->getToken()->getUser();

        $BusinessRepo = $this->em->getRepository('BemooveAppBundle:Business');
        $business = $BusinessRepo->findOneByOwner($account);

        if($business !== null) {
            $fromIp = $this->getClientIp();
            if('UNKNOWN' !== $fromIp) {
                $billingMandate = new BillingMandate();
                $billingMandate->setBusiness($business);
                $billingMandate->setFromIp($fromIp);
                $billingMandate->setSignOn(new \DateTime());
                $this->em->persist($billingMandate);
                $this->em->flush();
                return $billingMandate;
            }
        }
        return false;
    }

    private function getClientIp() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

            return $ipaddress;
    }
}
