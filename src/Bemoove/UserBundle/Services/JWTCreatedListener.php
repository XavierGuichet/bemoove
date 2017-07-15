<?php
// src/Bemoove/UserBundle/Services/JWTCreatedListener.php

namespace Bemoove\UserBundle\Services;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;
use Bemoove\AppBundle\Entity\Account;

class JWTCreatedListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    private $em;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, EntityManager $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        $payload       = $event->getData();
        $userdata = $this->em->getRepository('BemooveAppBundle:Account')->findOneByEmail($payload["username"]);
        $payload['id'] = $userdata->getId();
        $payload['ip'] = $request->getClientIp();

        $event->setData($payload);
    }
}
