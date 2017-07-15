<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * BankAccount
 *
 * @ApiResource(attributes={"filters"={"bankaccount.search"},
 *          "normalization_context"={"groups"={"bankAccount"}},
 *          "denormalization_context"={"groups"={"post_bankAccount"}},
 * })
 * @ORM\Table(name="bankAccount")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\BankAccountRepository")
 */
class BankAccount
{
    /**
     * @var int
     *
     * @Groups({"bankAccount"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"bankAccount","post_bankAccount"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Place\Address", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @Groups({"bankAccount","post_bankAccount"})
     * @ORM\Column(name="OwnerName", type="string", length=255, nullable=false)
     */
    private $ownerName;

    /**
     * @var \DateTime
     *
     * @Groups({"bankAccount","post_bankAccount"})
     * @ORM\Column(name="IBAN", type="string", length=255, nullable=false)
     */
    private $iban;

    /**
     * @Groups({"bankAccount"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Account")
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
     * Set ownerName
     *
     * @param string $ownerName
     *
     * @return BankAccount
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    /**
     * Get ownerName
     *
     * @return string
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * Set iban
     *
     * @param string $iban
     *
     * @return BankAccount
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set address
     *
     * @param \Bemoove\AppBundle\Entity\Place\Address $address
     *
     * @return BankAccount
     */
    public function setAddress(\Bemoove\AppBundle\Entity\Place\Address $address)
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
     * Set owner
     *
     * @param \Bemoove\AppBundle\Entity\Account $owner
     *
     * @return BankAccount
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
}
