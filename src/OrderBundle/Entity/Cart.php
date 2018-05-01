<?php

namespace OrderBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Cart
 *
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"cart", "person", "full_workoutinstance", "coach", "business_cart", "workout"}}
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
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\WorkoutInstance")
     */
    private $workoutInstance;

    /**
     * @var \DateTime
     *
     * @Groups({"cart"})
     * @ORM\Column(name="date_add", type="datetimetz")
     */
    private $dateAdd;

    /**
     * @var int
     *
     * @Groups({"cart"})
     * @ORM\Column(name="nb_booking", type="smallint")
     */
    private $nbBooking;

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
     * Set nbBooking
     *
     * @param integer $nbBooking
     *
     * @return Booking
     */
    public function setNbBooking($nbBooking)
    {
        $this->nbBooking = $nbBooking;

        return $this;
    }

    /**
     * Get nbBooking
     *
     * @return integer
     */
    public function getNbBooking()
    {
        return $this->nbBooking;
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
     * Set workoutInstance
     *
     * @param \Bemoove\AppBundle\Entity\WorkoutInstance $workoutInstance
     *
     * @return Booking
     */
    public function setWorkoutInstance(\Bemoove\AppBundle\Entity\WorkoutInstance $workoutInstance = null)
    {
        $this->workoutInstance = $workoutInstance;

        return $this;
    }

    /**
     * Get workoutInstance
     *
     * @return \Bemoove\AppBundle\Entity\WorkoutInstance
     */
    public function getWorkoutInstance()
    {
        return $this->workoutInstance;
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
}
