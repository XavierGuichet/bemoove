<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Bemoove\UserBundle\Entity\BaseUser as BaseUser;

/**
 * Sporty
 *
 * @ApiResource(attributes={
 * "normalization_context"={"groups"={"booking_with_user"}}
 * })
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\AccountRepository")
 */
class Account extends BaseUser
{
    /**
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Person", cascade={"persist", "merge"}))
     * @ORM\JoinColumn(nullable=true)
     * @ApiSubresource
     * @Groups({"booking_with_user"})
     */
    private $person;

    /**
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Business", mappedBy="owner", cascade={"persist", "merge"}))
     * @ORM\JoinColumn(nullable=true)
     * @ApiSubresource
     * @Groups({"booking_with_user"})
     */
    private $business;

    /**
     * @var string
     *
     * @ORM\Column(name="creationToken", type="string", length=8, unique=true, nullable=true)
     */
    private $creationToken;

    /**
     * @var boolean
     * @ORM\Column(name="isCoach", type="boolean", nullable=true)
     */
    private $isCoach = false;

    /**
     * Set person
     *
     * @param \Bemoove\AppBundle\Entity\Person $person
     *
     * @return Account
     */
    public function setPerson(\Bemoove\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Bemoove\AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set business
     *
     * @param \Bemoove\AppBundle\Entity\Business $business
     *
     * @return Account
     */
    public function setBusiness(\Bemoove\AppBundle\Entity\Business $business = null)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return \Bemoove\AppBundle\Entity\Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Set creationToken
     *
     * @param string $creationToken
     *
     * @return Account
     */
    public function setCreationToken($creationToken)
    {
        $this->creationToken = $creationToken;

        return $this;
    }

    /**
     * Get creationToken
     *
     * @return string
     */
    public function getCreationToken()
    {
        return $this->creationToken;
    }

    /**
     * Set isCoach
     *
     * @param boolean $isCoach
     *
     * @return Account
     */
    public function setIsCoach($isCoach)
    {
        $this->isCoach = $isCoach;

        return $this;
    }

    /**
     * Get isCoach
     *
     * @return boolean
     */
    public function getIsCoach()
    {
        return $this->isCoach;
    }
}
