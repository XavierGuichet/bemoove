<?php

namespace OrderBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * OrderStatus
 *
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"order_status"}}})
 * @ORM\Table(name="order_status")
 * @ORM\Entity(repositoryClass="OrderBundle\Repository\OrderStatusRepository")
 */
class OrderStatus
{
    const WAITINGPAYMENT = 1;
    const PAYMENTACCEPTED = 2;
    const DONE = 3;
    const PAYMENTERROR = 4;
    const CANCELED = 5;
    const WAINTINGREFUND = 6;
    const REFUNDED = 7;

    /**
     * @var int
     *
     * @Groups({"order_status"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string;
     * @Groups({"order_status"})
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
     private $name;

    /**
     * @var string;
     * @Groups({"order_status"})
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
     private $description;

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return OrderStatus
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set name
     *
     * @param string $name
     *
     * @return OrderStatus
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return OrderStatus
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
