<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * MailChangeRequest
 *
 * @ApiResource()
 * @ORM\Table(name="mail_change_request")
 * @ORM\Entity(repositoryClass="Bemoove\UserBundle\Repository\MailChangeRequestRepository")
 */
class MailChangeRequest
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="newemail", type="string", length=255)
     */
    private $newemail;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return MailChangeRequest
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set newemail
     *
     * @param string $newemail
     *
     * @return MailChangeRequest
     */
    public function setNewemail($newemail)
    {
        $this->newemail = $newemail;

        return $this;
    }

    /**
     * Get newemail
     *
     * @return string
     */
    public function getNewemail()
    {
        return $this->newemail;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return MailChangeRequest
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
