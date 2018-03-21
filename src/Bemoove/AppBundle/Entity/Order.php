<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Order
 *
 * @ApiResource()
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\OrderRepository")
 */
 class Order
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
      * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Cart")
      */
     private $cart;

     /**
      * @var string
      * @ORM\Column(name="order_number", type="string", length=255)
      */
     private $orderNumber;

     /**
      * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Person")
      */
     private $customer;

     /**
      * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Business")
      */
     private $seller;

     /**
      * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\OrderStatus")
      * @ORM\JoinColumn(nullable=true)
      */
     private $status;

     /**
      * @var \DateTime
      *
      * @ORM\Column(name="order_date", type="datetimetz")
      */
     private $orderDate;

     /**
      * @ORM\Column(name="payment", type="string", length=255)
      */
     private $payment;

     /**
      * @var float
      *
      * @ORM\Column(name="total_amount_tax_incl", type="float")
      */
     private $totalAmountTaxIncl;

     /**
      * @var float
      *
      * @ORM\Column(name="total_amount_tax_excl", type="float")
      */
     private $totalAmountTaxExcl;

     /**
     * @var float
     *
     * @ORM\Column(name="tax_rate", type="float")
     */
     private $taxRate;

     /**
      * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Invoice")
      * @ORM\JoinColumn(nullable=true)
      */
     private $invoice;

     /**
      * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Reservation")
      * @ORM\JoinColumn(nullable=true)
      */
     private $reservation;

     /**
      * Constructor
      */
     public function __construct()
     {
         // $this->reservation = new \Doctrine\Common\Collections\ArrayCollection();
         $this->setOrderNumber(uniqid());
         $this->setOrderDate(new \DateTime());
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

         if (!is_float($this->taxRate)) {
           throw new \Exception("Tax Rate is not set", 1);
         }
         $totalAmountTaxIncl = $this->totalAmountTaxExcl * (1 + $this->taxRate / 100);
         $this->totalAmountTaxIncl = $totalAmountTaxIncl;

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
      * Set status
      *
      * @param \Bemoove\AppBundle\Entity\OrderStatus $status
      *
      * @return Order
      */
     public function setStatus(\Bemoove\AppBundle\Entity\OrderStatus $status = null)
     {
         $this->status = $status;

         return $this;
     }

     /**
      * Get status
      *
      * @return \Bemoove\AppBundle\Entity\OrderStatus
      */
     public function getStatus()
     {
         return $this->status;
     }

     /**
      * Get reservation
      *
      * @return \Doctrine\Common\Collections\Collection
      */
     public function getReservation()
     {
         return $this->reservation;
     }

     /**
      * Set cart
      *
      * @param \Bemoove\AppBundle\Entity\Cart $cart
      *
      * @return Order
      */
     public function setCart(\Bemoove\AppBundle\Entity\Cart $cart = null)
     {
         $this->cart = $cart;

         return $this;
     }

     /**
      * Get cart
      *
      * @return \Bemoove\AppBundle\Entity\Cart
      */
     public function getCart()
     {
         return $this->cart;
     }

     /**
      * Set invoice
      *
      * @param \Bemoove\AppBundle\Entity\Invoice $invoice
      *
      * @return Order
      */
     public function setInvoice(\Bemoove\AppBundle\Entity\Invoice $invoice)
     {
         $this->invoice = $invoice;

         return $this;
     }

     /**
      * Get invoice
      *
      * @return \Bemoove\AppBundle\Entity\Invoice
      */
     public function getInvoice()
     {
         return $this->invoice;
     }

     /**
      * Set reservation
      *
      * @param \Bemoove\AppBundle\Entity\Reservation $reservation
      *
      * @return Order
      */
     public function setReservation(\Bemoove\AppBundle\Entity\Reservation $reservation = null)
     {
         $this->reservation = $reservation;

         return $this;
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
}
