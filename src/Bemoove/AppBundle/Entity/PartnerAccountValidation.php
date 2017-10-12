<?php

namespace Bemoove\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PartnerAccountValidation
 *
 * @ORM\Table(name="partner_account_validation")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\PartnerAccountValidationRepository")
 */
class PartnerAccountValidation
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
     * @var bool
     *
     * @ORM\Column(name="Business", type="boolean")
     */
    private $business;

    /**
     * @var bool
     *
     * @ORM\Column(name="LegalRepresentative", type="boolean")
     */
    private $legalRepresentative;

    /**
     * @var bool
     *
     * @ORM\Column(name="InvoiceAddress", type="boolean")
     */
    private $invoiceAddress;

    /**
     * @var bool
     *
     * @ORM\Column(name="InvoiceSettings", type="boolean")
     */
    private $invoiceSettings;

    /**
     * @var bool
     *
     * @ORM\Column(name="BankAccount", type="boolean")
     */
    private $bankAccount;

    /**
     * @var bool
     *
     * @ORM\Column(name="BemooveValidation", type="boolean")
     */
    private $bemooveValidation;

    /**
     * @var bool
     *
     * @ORM\Column(name="BillingMandate", type="boolean")
     */
    private $billingMandate;


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
     * Set business
     *
     * @param boolean $business
     *
     * @return PartnerAccountValidation
     */
    public function setBusiness($business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return bool
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Set legalRepresentative
     *
     * @param boolean $legalRepresentative
     *
     * @return PartnerAccountValidation
     */
    public function setLegalRepresentative($legalRepresentative)
    {
        $this->legalRepresentative = $legalRepresentative;

        return $this;
    }

    /**
     * Get legalRepresentative
     *
     * @return bool
     */
    public function getLegalRepresentative()
    {
        return $this->legalRepresentative;
    }

    /**
     * Set invoiceAddress
     *
     * @param boolean $invoiceAddress
     *
     * @return PartnerAccountValidation
     */
    public function setInvoiceAddress($invoiceAddress)
    {
        $this->invoiceAddress = $invoiceAddress;

        return $this;
    }

    /**
     * Get invoiceAddress
     *
     * @return bool
     */
    public function getInvoiceAddress()
    {
        return $this->invoiceAddress;
    }

    /**
     * Set invoiceSettings
     *
     * @param boolean $invoiceSettings
     *
     * @return PartnerAccountValidation
     */
    public function setInvoiceSettings($invoiceSettings)
    {
        $this->invoiceSettings = $invoiceSettings;

        return $this;
    }

    /**
     * Get invoiceSettings
     *
     * @return bool
     */
    public function getInvoiceSettings()
    {
        return $this->invoiceSettings;
    }

    /**
     * Set bankAccount
     *
     * @param boolean $bankAccount
     *
     * @return PartnerAccountValidation
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    /**
     * Get bankAccount
     *
     * @return bool
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * Set bemooveValidation
     *
     * @param boolean $bemooveValidation
     *
     * @return PartnerAccountValidation
     */
    public function setBemooveValidation($bemooveValidation)
    {
        $this->bemooveValidation = $bemooveValidation;

        return $this;
    }

    /**
     * Get bemooveValidation
     *
     * @return bool
     */
    public function getBemooveValidation()
    {
        return $this->bemooveValidation;
    }

    /**
     * Set billingMandate
     *
     * @param boolean $billingMandate
     *
     * @return PartnerAccountValidation
     */
    public function setBillingMandate($billingMandate)
    {
        $this->billingMandate = $billingMandate;

        return $this;
    }

    /**
     * Get billingMandate
     *
     * @return bool
     */
    public function getBillingMandate()
    {
        return $this->billingMandate;
    }
}
