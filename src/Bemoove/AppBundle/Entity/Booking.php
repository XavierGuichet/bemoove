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
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\User", inversedBy="profile", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Workout", inversedBy="workout")
     */
    private $workout;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Booking
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set workout
     *
     * @param integer $workout
     *
     * @return Booking
     */
    public function setWorkout($workout)
    {
        $this->workout = $workout;

        return $this;
    }

    /**
     * Get workout
     *
     * @return int
     */
    public function getWorkout()
    {
        return $this->workout;
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
     * Set nbPlace
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
     * Get nbPlace
     *
     * @return int
     */
    public function getNbBooking()
    {
        return $this->nbBooking;
    }
}
