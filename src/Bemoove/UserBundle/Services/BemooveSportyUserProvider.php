<?php
// src/AppBundle/Security/User/WebserviceUserProvider.php

namespace Bemoove\UserBundle\Services;
use Bemoove\AppBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class BemooveSportyUserProvider implements UserProviderInterface
{
    private $em;
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function loadUserByUsername($email)
    {
        $userData = $this->em->getRepository("BemooveAppBundle:Account")->findOneByEmail($email);
        if ($userData !== null) {
            return $userData;
        }
        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $email));
    }
    public function refreshUser(UserInterface $user)
    {

    }

    public function supportsClass($class) {
        return WebserviceUser::class === $class;
    }
}
