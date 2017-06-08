<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Workout
 *
 * @ApiResource(attributes={
 *          "filters"={"workout.coach","workout.startdate","workout.enddate"},
 *          "denormalization_context"={"groups"={"post_workout"}},
 *          "normalization_context"={"groups"={"workout"}}
 *  })
 * @ORM\Table(name="workout")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\WorkoutRepository")
 */
class Workout
{
    /**
     * @var int
     *
     * @Groups({"workout","post_workout"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"workout"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $coach;

    /**
     * @var string
     *
     * @Groups({"workout","post_workout"})
     * @ORM\Column(name="Name", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @Groups({"workout","post_workout"})
     * @ORM\Column(name="Startdate", type="datetimetz")
     */
    private $startdate;

    /**
     * @var \DateTime
     *
     * @Groups({"workout","post_workout"})
     * @ORM\Column(name="Enddate", type="datetimetz")
     */
    private $enddate;

    /**
     * @Groups({"workout","post_workout"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Sport", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $sport;

    /**
     * @Groups({"workout","post_workout"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Place\Address", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $address;

    /**
     * @var int
     *
     * @Groups({"workout","post_workout"})
     * @ORM\Column(name="Nb_Ticket_Available", type="smallint", nullable=true)
     */
    private $nbTicketAvailable;

    /**
     * @var int
     */
    private $nbTicketBooked;

    /**
     * @var boolean
     */
    private $soldOut;

    /**
     * @var \DateTime
     *
     * @Groups({"workout","post_workout"})
     */
    private $duration;

    /**
     * @var string
     *
     * @Groups({"workout","post_workout"})
     * @ORM\Column(name="Price", type="integer")
     */
    private $price;

    /**
     * @Groups({"workout","post_workout"})
     * @ORM\Column(name="Description", type="text")
     */
    protected $description;

    /**
     * @Groups({"workout","post_workout"})
     * @ORM\ManyToMany(targetEntity="Bemoove\AppBundle\Entity\Tag", cascade={"persist"})
     */
    protected $tags;

    /**
     * @Groups({"workout","post_workout"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $photo;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->nbTicketBooked = 0;
    }

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
     * Get duration
     *
     * @return \DateTime
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set nbTicketAvailable
     *
     * @param integer $nbTicketAvailable
     *
     * @return TrainingSession
     */
    public function setNbTicketAvailable($nbTicketAvailable)
    {
        $this->nbTicketAvailable = (int) $nbTicketAvailable;

        return $this;
    }

    /**
     * Get nbTicketAvailable
     *
     * @return int
     */
    public function getNbTicketAvailable()
    {
        return $this->nbTicketAvailable;
    }

    /**
     * Set nbTicketBooked
     *
     * @param integer $nbTicketBooked
     *
     * @return TrainingSession
     */
    public function setNbTicketBooked($nbTicketBooked)
    {
        $this->nbTicketBooked = $nbTicketBooked;

        if($this->nbTicketBooked >= $this->nbTicketAvailable) {
            $this->soldOut = true;
        }
        else {
            $this->soldOut = false;
        }

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
     * Get soldOut
     *
     * @return boolean
     */
    public function getsoldOut()
    {
        return $this->soldOut;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return TrainingSession
     */
    public function setPrice($price)
    {
        $this->price = (int) $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set coach
     *
     * @param \Bemoove\AppBundle\Entity\User $coach
     *
     * @return TrainingSession
     */
    public function setCoach(\Bemoove\AppBundle\Entity\User $coach)
    {
        $this->coach = $coach;

        return $this;
    }

    /**
     * Get coach
     *
     * @return \Bemoove\AppBundle\Entity\User
     */
    public function getCoach()
    {
        return $this->coach;
    }

    /**
     * Set sport
     *
     * @param \Bemoove\AppBundle\Entity\Sport $sport
     *
     * @return TrainingSession
     */
    public function setSport(\Bemoove\AppBundle\Entity\Sport $sport)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport
     *
     * @return \Bemoove\AppBundle\Entity\Sport
     */
    public function getSport()
    {
        return $this->sport;
    }

    /**
     * Set address
     *
     * @param \Bemoove\AppBundle\Entity\Place\Address $address
     *
     * @return TrainingSession
     */
    public function setAddress(\Bemoove\AppBundle\Entity\Place\Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \Bemoove\AppBundle\Entity\Place\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return TrainingSession
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add tag
     *
     * @param \Bemoove\AppBundle\Entity\Tag $tag
     *
     * @return TrainingSession
     */
    public function addTag(\Bemoove\AppBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Bemoove\AppBundle\Entity\Tag $tag
     */
    public function removeTag(\Bemoove\AppBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return TrainingSession
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set photo
     *
     * @param \Bemoove\AppBundle\Entity\Image $photo
     *
     * @return TrainingSession
     */
    public function setPhoto(\Bemoove\AppBundle\Entity\Image $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \Bemoove\AppBundle\Entity\Image
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     *
     * @return Workout
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
     * @return Workout
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
}
