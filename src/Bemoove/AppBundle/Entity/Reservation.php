<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reservation
 *
 * @ApiResource(attributes={
 *          "filters"={"person.account", "reservation.workoutinstance"},
 *          "normalization_context"={"groups"={"reservation","person","workout","full_workoutinstance"}},
 *          "denormalization_context"={"groups"={"post_person"}},})
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\OrderRepository")
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
     * @ORM\Column(name="order_id", type="string", length=8)
     */
    private $order;
    //@ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Order", inversedBy="reservation")

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
     * @ORM\Column(name="nb_booking", type="smallint")
     */
    private $nbBooking;
    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set order
     *
     * @param string $order
     *
     * @return Reservation
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
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
}
