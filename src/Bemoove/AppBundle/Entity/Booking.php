<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Booking
 *
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"booking_with_user"}}
 *  })
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @var int
     *
     * @Groups({"booking_with_user"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"booking_with_user"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Person", cascade={"persist"})
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\WorkoutInstance")
     */
    private $workoutInstance;

    /**
     * @var \DateTime
     *
     * @Groups({"booking_with_user"})
     * @ORM\Column(name="date_add", type="datetimetz")
     */
    private $dateAdd;

    /**
     * @var int
     *
     * @Groups({"booking_with_user"})
     * @ORM\Column(name="nb_booking", type="smallint")
     */
    private $nbBooking;

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
}
