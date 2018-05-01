<?php

namespace OrderBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Payment
 *
 * @ApiResource()
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="OrderBundle\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @var int
     *
     * @Groups({"payment"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"payment"})
     * @var int
     * @ORM\Column(name="mangoidtransaction", type="integer")
     */
    private $mangoIdTransaction;

    /**
     * @Groups({"payment"})
     * @var string
     * @ORM\Column(name="transactionRedirectUrl", type="string", length=255)
     */
    private $transactionRedirectUrl;

    /**
    * @Groups({"payment"})
    * @var string
    * @ORM\Column(name="status", type="string", length=255)
    */
    private $status;

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
     * Set mangoIdTransaction
     *
     * @param integer $mangoIdTransaction
     *
     * @return Payment
     */
    public function setMangoIdTransaction($mangoIdTransaction)
    {
        $this->mangoIdTransaction = $mangoIdTransaction;

        return $this;
    }

    /**
     * Get mangoIdTransaction
     *
     * @return integer
     */
    public function getMangoIdTransaction()
    {
        return $this->mangoIdTransaction;
    }

    /**
     * Set transactionRedirectUrl
     *
     * @param string $transactionRedirectUrl
     *
     * @return Payment
     */
    public function setTransactionRedirectUrl($transactionRedirectUrl)
    {
        $this->transactionRedirectUrl = $transactionRedirectUrl;

        return $this;
    }

    /**
     * Get transactionRedirectUrl
     *
     * @return string
     */
    public function getTransactionRedirectUrl()
    {
        return $this->transactionRedirectUrl;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Payment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
