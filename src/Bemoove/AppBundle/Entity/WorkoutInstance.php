<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * WorkoutInstance
 *
 * @ApiResource(attributes={
 *          "order"={"startdate": "ASC"},
 *          "filters"={"workoutinstance.workout","workoutinstance.coach","workoutinstance.startdate","workoutinstance.enddate"},
 *          "normalization_context"={"groups"={"full_workoutinstance","partial_coach","partial_workout","workout","image"}},})
 * @ORM\Table(name="workout_instance")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\WorkoutInstanceRepository")
 */
class WorkoutInstance
{
    /**
     * @var int
     *
     * @Groups({"full_workoutinstance"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"full_workoutinstance"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Coach")
     */
    private $coach;

    /**
     * @Groups({"full_workoutinstance"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Workout")
     */
    private $workout;

    /**
     * @var \DateTime
     *
     * @Groups({"full_workoutinstance"})
     * @ORM\Column(name="startdate", type="datetimetz")
     */
    private $startdate;

    /**
     * @var \DateTime
     *
     * @Groups({"full_workoutinstance"})
     * @ORM\Column(name="enddate", type="datetimetz")
     */
    private $enddate;

    /**
     * @var int
     *
     * @Groups({"full_workoutinstance"})
     * @ORM\Column(name="nbTicketAvailable", type="integer")
     */
    private $nbTicketAvailable;

    /**
     * @var int
     *
     * @Groups({"full_workoutinstance"})
     * @ORM\Column(name="nbTicketBooked", type="integer")
     */
    private $nbTicketBooked;

    /**
     * @var bool
     *
     * @Groups({"full_workoutinstance"})
     * @ORM\Column(name="soldOut", type="boolean", nullable=true)
     */
    private $soldOut;


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
     * Set startdate
     *
     * @param \DateTime $startdate
     *
     * @return WorkoutInstance
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     *
     * @return WorkoutInstance
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * Get enddate
     *
     * @return \DateTime
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set nbTicketBooked
     *
     * @param integer $nbTicketBooked
     *
     * @return WorkoutInstance
     */
    public function setNbTicketBooked($nbTicketBooked)
    {
        $this->nbTicketBooked = $nbTicketBooked;

        return $this;
    }

    /**
     * Get nbTicketBooked
     *
     * @return int
     */
    public function getNbTicketBooked()
    {
        return $this->nbTicketBooked;
    }

    /**
     * Set soldOut
     *
     * @param boolean $soldOut
     *
     * @return WorkoutInstance
     */
    public function setSoldOut($soldOut)
    {
        $this->soldOut = $soldOut;

        return $this;
    }

    /**
     * Get soldOut
     *
     * @return bool
     */
    public function getSoldOut()
    {
        return $this->soldOut;
    }

    /**
     * Set workout
     *
     * @param \Bemoove\AppBundle\Entity\Workout $workout
     *
     * @return WorkoutInstance
     */
    public function setWorkout(\Bemoove\AppBundle\Entity\Workout $workout = null)
    {
        $this->workout = $workout;

        return $this;
    }

    /**
     * Get workout
     *
     * @return \Bemoove\AppBundle\Entity\Workout
     */
    public function getWorkout()
    {
        return $this->workout;
    }

    /**
     * Set coach
     *
     * @param \Bemoove\AppBundle\Entity\Coach $coach
     *
     * @return WorkoutInstance
     */
    public function setCoach(\Bemoove\AppBundle\Entity\Coach $coach = null)
    {
        $this->coach = $coach;

        return $this;
    }

    /**
     * Get coach
     *
     * @return \Bemoove\AppBundle\Entity\Coach
     */
    public function getCoach()
    {
        return $this->coach;
    }

    /**
     * Set nbTicketAvailable
     *
     * @param integer $nbTicketAvailable
     *
     * @return WorkoutInstance
     */
    public function setNbTicketAvailable($nbTicketAvailable)
    {
        $this->nbTicketAvailable = $nbTicketAvailable;

        return $this;
    }

    /**
     * Get nbTicketAvailable
     *
     * @return integer
     */
    public function getNbTicketAvailable()
    {
        return $this->nbTicketAvailable;
    }
}
