<?php

namespace OrderBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Cart
 *
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"cart", "person", "full_workoutinstance", "coach", "business_cart", "workout"}},
 *          "denormalization_context"={"groups"={"cart"}}
 *  })
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="OrderBundle\Repository\CartRepository")
 */
class Cart
{
    /**
     * @var int
     *
     * @Groups({"cart"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"cart"})
     * @ORM\Column(name="originIp", type="string", length=255, nullable=true)
     */
    private $originIp;

    /**
     * @Groups({"cart"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Person")
     * @ORM\JoinColumn(nullable=true)
     */
    private $member;

    /**
     * @Groups({"cart"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Business")
     */
    private $seller;

    /**
     * @Groups({"cart"})
     * @ORM\ManyToMany(targetEntity="OrderBundle\Entity\CartProduct", cascade="persist")
     */
    private $products;

    /**
     * @var \DateTime
     *
     * @Groups({"cart"})
     * @ORM\Column(name="date_add", type="datetimetz")
     */
    private $dateAdd;

    /**
     * @var float
     *
     * @Groups({"cart"})
     * @ORM\Column(name="total_amount_tax_incl", type="float")
     */
    private $totalAmountTaxIncl;

    /**
     * @var float
     *
     * @Groups({"cart"})
     * @ORM\Column(name="total_amount_tax_excl", type="float")
     */
    private $totalAmountTaxExcl;

    /**
    * @var float
    *
    * @Groups({"cart"})
    * @ORM\Column(name="total_tax", type="float")
    */
    private $totalTax;

    /**
    * @var float
    *
    * @Groups({"cart"})
    * @ORM\Column(name="tax_rate", type="float")
    */
    private $taxRate;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDateAdd(new \DateTime());
        $this->taxRate = 0;
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
     * @return Booking
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
     * Set member
     *
     * @param \Bemoove\AppBundle\Entity\Person $member
     *
     * @return Booking
     */
    public function setMember(\Bemoove\AppBundle\Entity\Person $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \Bemoove\AppBundle\Entity\Person
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set originIp
     *
     * @param string $originIp
     *
     * @return Cart
     */
    public function setOriginIp($originIp)
    {
        $this->originIp = $originIp;

        return $this;
    }

    /**
     * Get originIp
     *
     * @return string
     */
    public function getOriginIp()
    {
        return $this->originIp;
    }

    /**
     * Add product
     *
     * @param \OrderBundle\Entity\CartProduct $product
     *
     * @return Cart
     */
    public function addProduct(\OrderBundle\Entity\CartProduct $product)
    {
        $this->products[] = $product;

        $this->updateTotalAmounts();

        return $this;
    }

    /**
     * Remove product
     *
     * @param \OrderBundle\Entity\CartProduct $product
     */
    public function removeProduct(\OrderBundle\Entity\CartProduct $product)
    {
        $this->products->removeElement($product);
        $this->updateTotalAmounts();
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function updateTotalAmounts() {
      $totalAmountTaxIncl = 0;
      $totalTaxAmount = 0;
      $totalAmountTaxExcl = 0;
      foreach($this->products as $product) {
        $productPriceTaxIncl = $product->getProduct()->getWorkout()->getPrice();
        $productPriceTax = round(($productPriceTaxIncl * $this->taxRate / 100),2, PHP_ROUND_HALF_UP);
        $productPriceTaxExcl = $productPriceTaxIncl - $productPriceTax;

        $totalAmountTaxIncl += $productPriceTaxIncl * $product->getQuantity();
        $totalTaxAmount += $productPriceTax * $product->getQuantity();
        $totalAmountTaxExcl += $productPriceTaxExcl * $product->getQuantity();
      }
      $this->totalAmountTaxIncl = $totalAmountTaxIncl;
      $this->totalTax = $totalTaxAmount;
      $this->totalAmountTaxExcl = $totalAmountTaxExcl;
      return $this;
    }

    /**
     * Set totalAmountTaxIncl
     *
     * @param float $totalAmountTaxIncl
     *
     * @return Cart
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
     * @return Cart
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
     * @return Cart
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;

        $this->updateTotalAmounts();

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
     * Set seller
     *
     * @param \Bemoove\AppBundle\Entity\Business $seller
     *
     * @return Cart
     */
    public function setSeller(\Bemoove\AppBundle\Entity\Business $seller = null)
    {
        $this->seller = $seller;

        $this->setTaxRate($seller->getVatRate());

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
     * Set totalTax
     *
     * @param float $totalTax
     *
     * @return Cart
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
