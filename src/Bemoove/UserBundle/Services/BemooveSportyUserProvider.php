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

// use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
// use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
// use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;

class BemooveSportyUserProvider implements UserProviderInterface
{
    private $em;
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
    //     // get property from provider configuration by provider name
    //     // , it will return `facebook_id` in that case (see service definition below)
    //     $property = $this->getProperty($response);
    //     $username = $response->getUsername(); // get the unique user identifier
    //
    //     //we "disconnect" previously connected users
    //     $existingUser = $this->userManager->findUserBy(array($property => $username));
    //     if (null !== $existingUser) {
    //         // set current user id and token to null for disconnect
    //         // ...
    //
    //         $this->userManager->updateUser($existingUser);
    //     }
    //     // we connect current user, set current user id and token
    //     // ...
    //     $this->userManager->updateUser($user);
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $userEmail = $response->getEmail();
        $user = $this->findUser(array("email" => $userEmail));
        // if null just create new user and set it properties
        if (null === $user) {
            var_dump("Sporty Provider");
            die();
            $email = $response->getEmail();
            $user = new User();
            $user->setEmail($email);
            $user->addRoles("ROLE_SPORTY");
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        }
        // else update access token of existing user
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        $user->$setter($response->getAccessToken());//update access token
        return $user;
    }

    public function loadUserByUsername($email)
    {
        $userData = $this->em->getRepository("BemooveAppBundle:Account")->findOneByEmail($email);
        if ($userData != null) {
            return $userData;
        }
        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $email));
    }
    public function refreshUser(UserInterface $user)
    {

    }

    public function supportsClass($class) {
        die("support class launched");
        return WebserviceUser::class === $class;
    }
}
