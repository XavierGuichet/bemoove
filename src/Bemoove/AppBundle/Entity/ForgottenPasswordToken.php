<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ForgottenPasswordToken.
 *
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"forgottenpasswordtoken"}},
 *          "denormalization_context"={"groups"={"post_person"}},})
 * @ORM\Table(name="forgottenpasswordtoken")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\ForgottenPasswordTokenRepository")
 */
class ForgottenPasswordToken
{
    /**
     * @var int
     *
     * @Groups({"forgottenpasswordtoken"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"forgottenpasswordtoken"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Account")
     */
    private $account;

    /**
     * @var string
     *
     * @Groups({"forgottenpasswordtoken"})
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="request_date", type="datetimetz")
     */
    private $requestDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetimetz")
     */
    private $expirationDate;

    /**
     * @var boolean
     * @ORM\Column(name="resetDone", type="boolean", nullable=true)
     */
    private $resetDone;

    /**
     * Constructor
     */
    public function __construct() {
        $this->setRequestDate(new \DateTime);
    }


    /**
     * Get id
     *
     * @return integer
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
     * @return ForgottenPasswordToken
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
     * Set requestDate
     *
     * @param \DateTime $requestDate
     *
     * @return ForgottenPasswordToken
     */
    public function setRequestDate($requestDate)
    {
        $this->requestDate = $requestDate;

        // Set expiration date in accordance to request Date
        $dateInterval1Day = new \DateInterval("P1D");
        $expirationDate = $requestDate;
        $expirationDate->add($dateInterval1Day);
        $this->setExpirationDate($expirationDate);

        return $this;
    }

    /**
     * Get requestDate
     *
     * @return \DateTime
     */
    public function getRequestDate()
    {
        return $this->requestDate;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     *
     * @return ForgottenPasswordToken
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Set resetDone
     *
     * @param boolean $resetDone
     *
     * @return ForgottenPasswordToken
     */
    public function setResetDone($resetDone)
    {
        $this->resetDone = $resetDone;

        return $this;
    }

    /**
     * Get resetDone
     *
     * @return boolean
     */
    public function getResetDone()
    {
        return $this->resetDone;
    }

    /**
     * Set account
     *
     * @param \Bemoove\AppBundle\Entity\Account $account
     *
     * @return ForgottenPasswordToken
     */
    public function setAccount(\Bemoove\AppBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Bemoove\AppBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}
