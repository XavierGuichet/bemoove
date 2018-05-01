<?php

namespace OrderBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * OrderHistory
 *
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"order_history", "order_status"}}})
 * @ORM\Table(name="order_history")
 * @ORM\Entity(repositoryClass="OrderBundle\Repository\OrderHistoryRepository")
 */
class OrderHistory
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
     * @ORM\ManyToOne(targetEntity="OrderBundle\Entity\Order", inversedBy="statusHistory", cascade={"persist"})
     */
    private $order;

    /**
     * @var OrderStatus
     *
     * @Groups({"order_history"})
     * @ORM\ManyToOne(targetEntity="OrderBundle\Entity\OrderStatus")
     */
    private $orderState;

    /**
     * @var \DateTime
     *
     * @Groups({"order_history"})
     * @ORM\Column(name="dateAdd", type="datetimetz")
     */
    private $dateAdd;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDateAdd(new \DateTime());
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
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return OrderHistory
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set order
     *
     * @param \OrderBundle\Entity\Order $order
     *
     * @return OrderHistory
     */
    public function setOrder(\OrderBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \OrderBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set orderState
     *
     * @param \OrderBundle\Entity\OrderStatus $orderState
     *
     * @return OrderHistory
     */
    public function setOrderState(\OrderBundle\Entity\OrderStatus $orderState = null)
    {
        $this->orderState = $orderState;

        return $this;
    }

    /**
     * Get orderState
     *
     * @return \OrderBundle\Entity\OrderStatus
     */
    public function getOrderState()
    {
        return $this->orderState;
    }
}
