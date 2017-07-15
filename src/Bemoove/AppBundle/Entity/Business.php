<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Business
 *
 * @ApiResource()
 * @ORM\Table(name="business")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\BusinessRepository")
 */
class Business
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
     * @ORM\Column(name="LegalName", type="string", length=255)
     */
    private $legalName;

    /**
     * @var string
     *
     * @ORM\Column(name="CommonName", type="string", length=255, nullable=true)
     */
    private $commonName;

    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Place\Address")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="Mail", type="string", length=255)
     */
    private $mail;

     /**
      * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Person")
      */
    private $legalRepresentative;

    /**
     * @var string
     *
     * @ORM\Column(name="LegalStatus", type="string", length=255)
     */
    private $legalStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="Siret", type="string", length=255)
     */
    private $siret;

    /**
     * @var float
     *
     * @ORM\Column(name="ShareCapital", type="float", nullable=true)
     */
    private $shareCapital;

    /**
     * @var string
     *
     * @ORM\Column(name="RCSNumber", type="string", length=255, nullable=true)
     */
    private $RCSNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="NAFCode", type="string", length=255, nullable=true)
     */
    private $NAFCode;

    /**
     * @var string
     *
     * @ORM\Column(name="APECode", type="string", length=255, nullable=true)
     */
    private $APECode;

    /**
     * @var string
     *
     * @ORM\Column(name="TVANumber", type="string", length=255, nullable=true)
     */
    private $TVANumber;

    /**
     * @var float
     *
     * @ORM\Column(name="TaxRate", type="float", nullable=true)
     */
    private $taxRate;

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
     * Set nAFCode
     *
     * @param string $nAFCode
     *
     * @return Business
     */
    public function setNAFCode($nAFCode)
    {
        $this->NAFCode = $nAFCode;

        return $this;
    }

    /**
     * Get nAFCode
     *
     * @return string
     */
    public function getNAFCode()
    {
        return $this->NAFCode;
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
     * Set tVANumber
     *
     * @param string $tVANumber
     *
     * @return Business
     */
    public function setTVANumber($tVANumber)
    {
        $this->TVANumber = $tVANumber;

        return $this;
    }

    /**
     * Get tVANumber
     *
     * @return string
     */
    public function getTVANumber()
    {
        return $this->TVANumber;
    }

    /**
     * Set taxRate
     *
     * @param float $taxRate
     *
     * @return Business
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Get taxRate
     *
     * @return float
     */
    public function getTaxRate()
    {
        return $this->taxRate;
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
}
