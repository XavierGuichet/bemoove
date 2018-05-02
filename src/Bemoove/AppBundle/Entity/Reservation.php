<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reservation
 *
 * @ApiResource(attributes={
 *          "filters"={"reservation.futureworkoutinstance", "reservation.person_filter", "reservation.workoutinstance"},
 *          "normalization_context"={"groups"={"reservation","person","workout","full_workoutinstance"}},
 *          "denormalization_context"={"groups"={"post_person"}},})
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @Groups({"reservation"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"reservation"})
     * @ORM\ManyToOne(targetEntity="OrderBundle\Entity\Order", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $order;

    /**
     * @Groups({"reservation"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Person")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @var \DateTime
     *
     * @Groups({"reservation"})
     * @ORM\Column(name="date_add", type="datetimetz")
     */
    private $dateAdd;

    /**
     * @Groups({"reservation"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\WorkoutInstance")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workoutInstance;

    /**
     * @var int
     *
     * @Groups({"reservation"})
     * @ORM\Column(name="nb_booking", type="smallint")
     */
    private $nbBooking;

    /**
     * @var float
     *
     * @Groups({"reservation"})
     * @ORM\Column(name="unit_price_tax_incl", type="float")
     */
    private $unitPriceTaxIncl;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDateAdd(new \DateTime());
        // $this->person = new \Doctrine\Common\Collections\ArrayCollection();
        // $this->workoutInstance = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Reservation
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
     * @return Reservation
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
     * Set workoutInstance
     *
     * @param \Bemoove\AppBundle\Entity\WorkoutInstance $workoutInstance
     *
     * @return Reservation
     */
    public function setWorkoutInstance(\Bemoove\AppBundle\Entity\WorkoutInstance $workoutInstance)
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
     * Set person
     *
     * @param \Bemoove\AppBundle\Entity\Person $person
     *
     * @return Reservation
     */
    public function setPerson(\Bemoove\AppBundle\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Bemoove\AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set order
     *
     * @param \OrderBundle\Entity\Order $order
     *
     * @return Reservation
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
     * Set unitPriceTaxIncl
     *
     * @param float $unitPriceTaxIncl
     *
     * @return Reservation
     */
    public function setUnitPriceTaxIncl($unitPriceTaxIncl)
    {
        $this->unitPriceTaxIncl = $unitPriceTaxIncl;

        return $this;
    }

    /**
     * Get unitPriceTaxIncl
     *
     * @return float
     */
    public function getUnitPriceTaxIncl()
    {
        return $this->unitPriceTaxIncl;
    }
}
