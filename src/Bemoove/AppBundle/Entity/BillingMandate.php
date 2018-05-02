<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Booking
 *
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={""}}
 *  })
 * @ORM\Table(name="billingmandate")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\BillingMandateRepository")
 */
class BillingMandate
{
    /**
     * @var int
     *
     * @Groups({""})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({""})
     * @ORM\Column(name="sign_on", type="datetimetz")
     */
    private $signOn;

    /**
      * @Groups({""})
     * @ORM\Column(name="form_ip", type="string", length=255, nullable=true)
     */
    private $fromIp;

    /**
     * @Groups({""})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Business")
     * @ORM\JoinColumn(nullable=false)
     */
    private $business;

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
     * Set signOn
     *
     * @param \DateTime $signOn
     *
     * @return BillingMandate
     */
    public function setSignOn($signOn)
    {
        $this->signOn = $signOn;

        return $this;
    }

    /**
     * Get signOn
     *
     * @return \DateTime
     */
    public function getSignOn()
    {
        return $this->signOn;
    }

    /**
     * Set fromIp
     *
     * @param string $fromIp
     *
     * @return BillingMandate
     */
    public function setFromIp($fromIp)
    {
        $this->fromIp = $fromIp;

        return $this;
    }

    /**
     * Get fromIp
     *
     * @return string
     */
    public function getFromIp()
    {
        return $this->fromIp;
    }

    /**
     * Set business
     *
     * @param \Bemoove\AppBundle\Entity\Business $business
     *
     * @return BillingMandate
     */
    public function setBusiness(\Bemoove\AppBundle\Entity\Business $business)
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
}
