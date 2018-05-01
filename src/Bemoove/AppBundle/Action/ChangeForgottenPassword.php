<?php
// src/Bemoove/AppBundle/Action/SendForgottenPasswordToken.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\ForgottenPasswordToken;
use Bemoove\AppBundle\Entity\Account;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Doctrine\ORM\EntityManagerInterface;

use Bemoove\AppBundle\Services\MyMail;
use ApiPlatform\Core\Exception\InvalidArgumentException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChangeForgottenPassword
{
    private $encoderFactory;
    private $mailer;
    private $securityTokenStorage;
    private $em;

    public function __construct(EncoderFactoryInterface $encoderFactory, TokenStorageInterface $securityTokenStorage, EntityManagerInterface $em, MyMail $mailer)
    {
        $this->encoderFactory = $encoderFactory;
        $this->securityTokenStorage = $securityTokenStorage;
        $this->em = $em;
        $this->mailer = $mailer;
    }

    /**
     * @Route(
     *     name="changeForgottenPassword",
     *     path="/change_forgotten_password",
     *     defaults={"_api_receive"=false, "_api_resource_class"=ForgottenPasswordToken::class, "_api_collection_operation_name"="changeForgottenPassword"}
     * )
     * @Method("POST")
     */
    public function __invoke(Request $request)
    {
        $content = $request->getContent();

        $json = json_decode($content);
        if(!$json) {
            throw new InvalidArgumentException("Can't decode JSON");
        }

        if(!property_exists($json, 'token')) {
            throw new InvalidArgumentException("JSON should contain a token property");
        }

        if(!property_exists($json, 'password')) {
            throw new InvalidArgumentException("JSON should contain a password property");
        }

        $token = $json->token;
        $password = $json->password;

        $forgottenPasswordTokenRepo = $this->em->getRepository('BemooveAppBundle:ForgottenPasswordToken');

        $forgottenPasswordToken = $forgottenPasswordTokenRepo->findOneByToken($token);

        if(!$forgottenPasswordToken) {
            throw new \Exception('No forgotten password procedure with token : '.$token);
        }

        if(new \DateTime() >= $forgottenPasswordToken->getExpirationDate()) {
            throw new \Exception('This forgotten password procedure is expired.');
        }

        if($forgottenPasswordToken->getResetDone()) {
            throw new \Exception('This forgotten password procedure was already used.');
        }

        $account = $forgottenPasswordToken->getAccount();
        if(!$account) {
            throw new \Exception('Can\'t find account');
        }

        $account->setPassword($password);
        $this->encodePWd($account);


        $this->mailer->sendPasswordChangedMail($account->getEmail());
    }

    public function encodePWd(Account $account)
    {
        $plainPassword = $account->getPassword();
        $encoder = $this->encoderFactory->getEncoder($account);

        if ($encoder instanceof BCryptPasswordEncoder) {
            $account->setSalt(null);
        }
        else {
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $account->setSalt($salt);
        }

        $hashedPassword = $encoder->encodePassword($plainPassword, $account->getSalt());

        $account->setPassword($hashedPassword);
        $account->eraseCredentials();

        $this->em->persist($account);
        $this->em->flush();
    }
}
