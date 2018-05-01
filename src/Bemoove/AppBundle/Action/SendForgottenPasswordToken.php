<?php
// src/Bemoove/AppBundle/Action/SendForgottenPasswordToken.php

namespace Bemoove\AppBundle\Action;

use Bemoove\AppBundle\Entity\ForgottenPasswordToken;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

use Bemoove\AppBundle\Services\MyMail;
use ApiPlatform\Core\Exception\InvalidArgumentException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SendForgottenPasswordToken
{
    private $mailer;
    private $em;

    public function __construct(EntityManagerInterface $em, MyMail $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    /**
     * @Route(
     *     name="sendForgottenPasswordToken",
     *     path="/send_forgotten_password_token",
     *     defaults={"_api_receive"=false, "_api_resource_class"=ForgottenPasswordToken::class, "_api_collection_operation_name"="sendForgottenPasswordToken"}
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

        if(!property_exists($json, 'email') || !filter_var($json->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("JSON should contain a valid email property");
        }

        $email = $json->email;

        $accountRepo = $this->em->getRepository('BemooveAppBundle:Account');

        $account = $accountRepo->findOneByUsername($email);

        if(!$account) {
            throw new \Exception('No account for this email : '.$email);
        }

        $forgottenPasswordToken = new ForgottenPasswordToken();
        $forgottenPasswordToken->setAccount($account);

        $token = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

        $forgottenPasswordToken->setToken($token);

        $this->em->persist($forgottenPasswordToken);
        $this->em->flush();

        $this->mailer->sendForgottenPasswordTokenMail($account->getEmail(), $token);

        return $forgottenPasswordToken;
    }
}
