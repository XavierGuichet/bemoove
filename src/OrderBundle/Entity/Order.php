<?php

namespace OrderBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use OrderBundle\Entity\OrderHistory;

/**
 * Order
 *
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"order","payment","order_history", "order_status"}}})
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="OrderBundle\Repository\OrderRepository")
 */
 class Order
 {
     /**
      * @var int
      *
      * @Groups({"order"})
      * @ORM\Column(name="id", type="integer")
      * @ORM\Id
      * @ORM\GeneratedValue(strategy="AUTO")
      */
     private $id;

     /**
      * @ORM\OneToOne(targetEntity="OrderBundle\Entity\Cart")
      */
     private $cart;

     /**
      * @var string
      * @Groups({"order"})
      * @ORM\Column(name="order_number", type="string", length=255)
      */
     private $orderNumber;

     /**
      * @Groups({"order"})
      * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Person")
      */
     private $customer;

     /**
      * @Groups({"order"})
      * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Business")
      */
     private $seller;

     /**
      * @Groups({"order"})
      * @ORM\OneToMany(targetEntity="OrderBundle\Entity\OrderHistory", mappedBy="order", cascade={"persist"})
      * @ORM\JoinColumn(nullable=true)
      */
     private $statusHistory;

     /**
      * @var \DateTime
      *
      * @Groups({"order"})
      * @ORM\Column(name="order_date", type="datetimetz")
      */
     private $orderDate;

     /**
      * @Groups({"order"})
      * @ORM\OneToOne(targetEntity="OrderBundle\Entity\Payment")
      */
     private $payment;

     /**
      * @var float
      *
      * @Groups({"order"})
      * @ORM\Column(name="total_amount_tax_incl", type="float")
      */
     private $totalAmountTaxIncl;

     /**
      * @var float
      *
      * @Groups({"order"})
      * @ORM\Column(name="total_amount_tax_excl", type="float")
      */
     private $totalAmountTaxExcl;

     /**
     * @var float
     *
     * @Groups({"order"})
     * @ORM\Column(name="total_tax", type="float")
     */
     private $totalTax;

     /**
     * @var float
     *
     * @Groups({"order"})
     * @ORM\Column(name="tax_rate", type="float")
     */
     private $taxRate;

     /**
      * @Groups({"order"})
      * @ORM\OneToOne(targetEntity="OrderBundle\Entity\Invoice")
      * @ORM\JoinColumn(nullable=true)
      */
     private $invoice;

     /**
      * @Groups({"order"})
      * @ORM\OneToMany(targetEntity="Bemoove\AppBundle\Entity\Reservation", mappedBy="order")
      * @ORM\JoinColumn(nullable=true)
      */
     private $reservations;

     /**
      * Constructor
      */
     public function __construct()
     {
         $this->statusHistory = new ArrayCollection();
         $this->reservations = new ArrayCollection();
         $this->setOrderNumber(uniqid());
         $this->setOrderDate(new \DateTime());
     }

     public function updateOrderTotalAmounts() {
       $totalAmountTaxIncl = 0;
       $totalTaxAmount = 0;
       $totalAmountTaxExcl = 0;
       foreach($this->reservations as $reservation) {
         $productPriceTaxIncl = $reservation->getUnitPriceTaxIncl();
         $productPriceTax = round(($productPriceTaxIncl * $this->taxRate / 100),2, PHP_ROUND_HALF_UP);
         $productPriceTaxExcl = $productPriceTaxIncl - $productPriceTax;

         $totalAmountTaxIncl += $productPriceTaxIncl * $reservation->getNbBooking();
         $totalTaxAmount += $productPriceTax * $reservation->getNbBooking();
         $totalAmountTaxExcl += $productPriceTaxExcl * $reservation->getNbBooking();
       }
       $this->totalAmountTaxIncl = $totalAmountTaxIncl;
       $this->totalTax = $totalTaxAmount;
       $this->totalAmountTaxExcl = $totalAmountTaxExcl;
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
      * Set orderNumber
      *
      * @param string $orderNumber
      *
      * @return Order
      */
     public function setOrderNumber($orderNumber)
     {
         $this->orderNumber = $orderNumber;

         return $this;
     }

     /**
      * Get orderNumber
      *
      * @return string
      */
     public function getOrderNumber()
     {
         return $this->orderNumber;
     }

     /**
      * Set orderDate
      *
      * @param integer $orderDate
      *
      * @return Order
      */
     public function setOrderDate($orderDate)
     {
         $this->orderDate = $orderDate;

         return $this;
     }

     /**
      * Get orderDate
      *
      * @return integer
      */
     public function getOrderDate()
     {
         return $this->orderDate;
     }

     /**
      * Set totalAmountTaxIncl
      *
      * @param float $totalAmountTaxIncl
      *
      * @return Order
      */
     public function setTotalAmountTaxIncl($totalAmountTaxIncl)
     {
         $this->totalAmountTaxIncl = $totalAmountTaxIncl;

         return $this;
     }

     /**
      * Get totalAmountTaxIncl
      *
      * @return float
      */
     public function getTotalAmountTaxIncl()
     {
         return $this->totalAmountTaxIncl;
     }

     /**
      * Set totalAmountTaxExcl
      *
      * @param float $totalAmountTaxExcl
      *
      * @return Order
      */
     public function setTotalAmountTaxExcl($totalAmountTaxExcl)
     {
         $this->totalAmountTaxExcl = $totalAmountTaxExcl;

         return $this;
     }

     /**
      * Get totalAmountTaxExcl
      *
      * @return float
      */
     public function getTotalAmountTaxExcl()
     {
         return $this->totalAmountTaxExcl;
     }

     /**
      * Set taxRate
      *
      * @param float $taxRate
      *
      * @return Order
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
      * Set customer
      *
      * @param \Bemoove\AppBundle\Entity\Person $customer
      *
      * @return Order
      */
     public function setCustomer(\Bemoove\AppBundle\Entity\Person $customer = null)
     {
         $this->customer = $customer;

         return $this;
     }

     /**
      * Get customer
      *
      * @return \Bemoove\AppBundle\Entity\Person
      */
     public function getCustomer()
     {
         return $this->customer;
     }

     /**
      * Set seller
      *
      * @param \Bemoove\AppBundle\Entity\Business $seller
      *
      * @return Order
      */
     public function setSeller(\Bemoove\AppBundle\Entity\Business $seller = null)
     {
         $this->seller = $seller;

         return $this;
     }

     /**
      * Get seller
      *
      * @return \Bemoove\AppBundle\Entity\Business
      */
     public function getSeller()
     {
         return $this->seller;
     }

     /**
      * Set cart
      *
      * @param \OrderBundle\Entity\Cart $cart
      *
      * @return Order
      */
     public function setCart(\OrderBundle\Entity\Cart $cart = null)
     {
         $this->cart = $cart;

         return $this;
     }

     /**
      * Get cart
      *
      * @return \OrderBundle\Entity\Cart
      */
     public function getCart()
     {
         return $this->cart;
     }

     /**
      * Set invoice
      *
      * @param \OrderBundle\Entity\Invoice $invoice
      *
      * @return Order
      */
     public function setInvoice(\OrderBundle\Entity\Invoice $invoice)
     {
         $this->invoice = $invoice;

         return $this;
     }

     /**
      * Get invoice
      *
      * @return \OrderBundle\Entity\Invoice
      */
     public function getInvoice()
     {
         return $this->invoice;
     }

    /**
     * Set payment
     *
     * @param string $payment
     *
     * @return Order
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return string
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Get Current status
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCurrentStatus()
    {
        $statusHistory = $this->getStatusHistory()->toArray();
        usort($statusHistory, function(OrderHistory $a, OrderHistory $b) {
          return $b->getDateAdd()->getTimestamp() - $a->getDateAdd()->getTimestamp();
        });
        return $statusHistory[0];
    }

    /**
     * Add statusHistory
     *
     * @param \OrderBundle\Entity\OrderHistory $statusHistory
     *
     * @return Order
     */
    public function addStatusHistory(\OrderBundle\Entity\OrderHistory $statusHistory)
    {
        $this->statusHistory[] = $statusHistory;

        return $this;
    }

    /**
     * Remove statusHistory
     *
     * @param \OrderBundle\Entity\OrderHistory $statusHistory
     */
    public function removeStatusHistory(\OrderBundle\Entity\OrderHistory $statusHistory)
    {
        $this->statusHistory->removeElement($statusHistory);
    }

    /**
     * Get statusHistory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatusHistory()
    {
        return $this->statusHistory;
    }

    /**
     * Add reservation
     *
     * @param \Bemoove\AppBundle\Entity\Reservation $reservation
     *
     * @return Order
     */
    public function addReservation(\Bemoove\AppBundle\Entity\Reservation $reservation)
    {
      dump($reservation);
        $this->reservations[] = $reservation;

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \Bemoove\AppBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\Bemoove\AppBundle\Entity\Reservation $reservation)
    {
        $this->reservations->removeElement($reservation);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * Set totalTax
     *
     * @param float $totalTax
     *
     * @return Order
     */
    public function setTotalTax($totalTax)
    {
        $this->totalTax = $totalTax;

        return $this;
    }

    /**
     * Get totalTax
     *
     * @return float
     */
    public function getTotalTax()
    {
        return $this->totalTax;
    }
}
