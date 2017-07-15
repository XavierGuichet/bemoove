<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * RegistrationToken
 *
 * @ApiResource()
 * @ORM\Table(name="registration_token")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\RegistrationTokenRepository")
 */
class RegistrationToken
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
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=8, unique=true)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUsed", type="datetime", nullable=true)
     */
    private $dateUsed;


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
     * Set token
     *
     * @param string $token
     *
     * @return RegistrationToken
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

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return RegistrationToken
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateUsed
     *
     * @param \DateTime $dateUsed
     *
     * @return RegistrationToken
     */
    public function setDateUsed($dateUsed)
    {
        $this->dateUsed = $dateUsed;

        return $this;
    }

    /**
     * Get dateUsed
     *
     * @return \DateTime
     */
    public function getDateUsed()
    {
        return $this->dateUsed;
    }
}
