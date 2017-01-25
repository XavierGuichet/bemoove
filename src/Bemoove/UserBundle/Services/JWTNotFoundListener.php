<?php
// src/Bemoove/UserBundle/Services/JWTCreatedListener.php

namespace Bemoove\UserBundle\Services;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JWTNotFoundListener
{
    /**
 * @param JWTNotFoundEvent $event
 */
public function onJWTNotFound(JWTNotFoundEvent $event)
{
    $data = [
        'status'  => '403 Forbidden',
        'message' => 'Missing token',
    ];

    $response = new JsonResponse($data, 403);

    $event->setResponse($response);
}
}
