<?php

namespace OrderBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * CartProduct
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"cart", "person", "full_workoutinstance", "coach", "business_cart", "workout"}},
 *          "denormalization_context"={"groups"={"cart"}}
 *  })
 * @ORM\Table(name="cart_product")
 * @ORM\Entity(repositoryClass="OrderBundle\Repository\CartProductRepository")
 */
class CartProduct
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
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\WorkoutInstance")
     */
    private $product;

    /**
     * @var int
     *
     * @Groups({"cart"})
     * @ORM\Column(name="Quantity", type="integer")
     */
    private $quantity;

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
     * Set product
     *
     * @param \stdClass $product
     *
     * @return CartProduct
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \stdClass
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return CartProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
