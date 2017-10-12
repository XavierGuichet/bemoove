<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Business
 *
 * @ApiResource(attributes={"filters"={"business.search"},
 *          "normalization_context"={"groups"={"business"}}
 * })
 * @ORM\Table(name="business")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\BusinessRepository")
 */
class Business
{
    /**
     * @var int
     *
     * @Groups({"business"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"business"})
     * @ORM\Column(name="LegalName", type="string", length=255, nullable=true)
     */
    private $legalName;

    /**
     * @var string
     *
     * @Groups({"business"})
     * @ORM\Column(name="CommonName", type="string", length=255, nullable=true)
     */
    private $commonName;

    /**
     * @var string
     *
     * @Groups({"business"})
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Place\Address", cascade={"persist","remove"}, fetch="EAGER")
     */
    private $address;

    /**
     * @var string
     *
     * @Groups({"business"})
     * @ORM\Column(name="Mail", type="string", length=255, nullable=true)
     */
    private $mail;

     /**
      * @Groups({"business"})
      * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Person", cascade={"persist","remove"}, fetch="EAGER")
      */
    private $legalRepresentative;

    /**
     * @var string
     *
     * @Groups({"business"})
     * @ORM\Column(name="LegalStatus", type="string", length=255, nullable=true)
     */
    private $legalStatus;

    /**
     * @var string
     *
     * @Groups({"business"})
     * @ORM\Column(name="Siret", type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @var float
     *
     * @Groups({"business"})
     * @ORM\Column(name="ShareCapital", type="float", nullable=true)
     */
    private $shareCapital;

    /**
     * @var string
     *
     * @Groups({"business"})
     * @ORM\Column(name="RCSNumber", type="string", length=255, nullable=true)
     */
    private $RCSNumber;

    /**
     * @var string
     *
     * @Groups({"business"})
     * @ORM\Column(name="APECode", type="string", length=255, nullable=true)
     */
    private $APECode;

    /**
     * @var string
     *
     * @Groups({"business"})
     * @ORM\Column(name="VATNumber", type="string", length=255, nullable=true)
     */
    private $vatNumber;

    /**
     * @var float
     *
     * @Groups({"business"})
     * @ORM\Column(name="VATRate", type="float", nullable=true)
     */
    private $vatRate;

    /**
     * @Groups({"business"})
     * @ORM\OneToMany(targetEntity="Bemoove\AppBundle\Entity\Coach", mappedBy="business")
     */
    private $coaches;

    /**
     * @Groups({"business"})
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Account", inversedBy="business")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

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
     * Set legalName
     *
     * @param string $legalName
     *
     * @return Business
     */
    public function setLegalName($legalName)
    {
        $this->legalName = $legalName;

        return $this;
    }

    /**
     * Get legalName
     *
     * @return string
     */
    public function getLegalName()
    {
        return $this->legalName;
    }

    /**
     * Set commonName
     *
     * @param string $commonName
     *
     * @return Business
     */
    public function setCommonName($commonName)
    {
        $this->commonName = $commonName;

        return $this;
    }

    /**
     * Get commonName
     *
     * @return string
     */
    public function getCommonName()
    {
        return $this->commonName;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Business
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set legalStatus
     *
     * @param string $legalStatus
     *
     * @return Business
     */
    public function setLegalStatus($legalStatus)
    {
        $this->legalStatus = $legalStatus;

        return $this;
    }

    /**
     * Get legalStatus
     *
     * @return string
     */
    public function getLegalStatus()
    {
        return $this->legalStatus;
    }

    /**
     * Set siret
     *
     * @param string $siret
     *
     * @return Business
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set shareCapital
     *
     * @param float $shareCapital
     *
     * @return Business
     */
    public function setShareCapital($shareCapital)
    {
        $this->shareCapital = $shareCapital;

        return $this;
    }

    /**
     * Get shareCapital
     *
     * @return float
     */
    public function getShareCapital()
    {
        return $this->shareCapital;
    }

    /**
     * Set rCSNumber
     *
     * @param string $rCSNumber
     *
     * @return Business
     */
    public function setRCSNumber($rCSNumber)
    {
        $this->RCSNumber = $rCSNumber;

        return $this;
    }

    /**
     * Get rCSNumber
     *
     * @return string
     */
    public function getRCSNumber()
    {
        return $this->RCSNumber;
    }

    /**
     * Set aPECode
     *
     * @param string $aPECode
     *
     * @return Business
     */
    public function setAPECode($aPECode)
    {
        $this->APECode = $aPECode;

        return $this;
    }

    /**
     * Get aPECode
     *
     * @return string
     */
    public function getAPECode()
    {
        return $this->APECode;
    }

    /**
     * Set address
     *
     * @param \Bemoove\AppBundle\Entity\Place\Address $address
     *
     * @return Business
     */
    public function setAddress(\Bemoove\AppBundle\Entity\Place\Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \Bemoove\AppBundle\Entity\Place\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set legalRepresentative
     *
     * @param \Bemoove\AppBundle\Entity\Person $legalRepresentative
     *
     * @return Business
     */
    public function setLegalRepresentative(\Bemoove\AppBundle\Entity\Person $legalRepresentative = null)
    {
        $this->legalRepresentative = $legalRepresentative;

        return $this;
    }

    /**
     * Get legalRepresentative
     *
     * @return \Bemoove\AppBundle\Entity\Person
     */
    public function getLegalRepresentative()
    {
        return $this->legalRepresentative;
    }

    /**
     * Set owner
     *
     * @param \Bemoove\AppBundle\Entity\Account $owner
     *
     * @return Business
     */
    public function setOwner(\Bemoove\AppBundle\Entity\Account $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Bemoove\AppBundle\Entity\Account
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set vatRate
     *
     * @param float $vatRate
     *
     * @return Business
     */
    public function setVatRate($vatRate)
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    /**
     * Get vatRate
     *
     * @return float
     */
    public function getVatRate()
    {
        return $this->vatRate;
    }

    /**
     * Set vatNumber
     *
     * @param string $vatNumber
     *
     * @return Business
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    /**
     * Get vatNumber
     *
     * @return string
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->coaches = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add coach
     *
     * @param \Bemoove\AppBundle\Entity\Coach $coach
     *
     * @return Business
     */
    public function addCoach(\Bemoove\AppBundle\Entity\Coach $coach)
    {
        $this->coaches[] = $coach;

        return $this;
    }

    /**
     * Remove coach
     *
     * @param \Bemoove\AppBundle\Entity\Coach $coach
     */
    public function removeCoach(\Bemoove\AppBundle\Entity\Coach $coach)
    {
        $this->coaches->removeElement($coach);
    }

    /**
     * Get coaches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoaches()
    {
        return $this->coaches;
    }
}
