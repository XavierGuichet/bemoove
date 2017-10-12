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
 *          "filters"={"workout.coach","workout.owner"},
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
     * @Groups({"workout","post_workout","partial_workout"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"workout"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Coach")
     * @ORM\JoinColumn(nullable=true)
     */
    private $coach;

    /**
     * @var string
     *
     * @Groups({"workout","post_workout","partial_workout"})
     * @ORM\Column(name="Name", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @Groups({"workout","post_workout","partial_workout"})
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
     * @var \DateTime
     *
     * @ORM\Column(name="duration", type="integer")
     * @Groups({"workout","post_workout","partial_workout"})
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
     * @ORM\Column(name="Notice", type="text")
     */
    protected $notice;

    /**
     * @Groups({"workout","post_workout"})
     * @ORM\Column(name="Outfit", type="text")
     */
    protected $outfit;

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

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Account")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;

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
     * Set title
     *
     * @param string $title
     *
     * @return Workout
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
     * Set notice
     *
     * @param string $notice
     *
     * @return Workout
     */
    public function setNotice($notice)
    {
        $this->notice = $notice;

        return $this;
    }

    /**
     * Get notice
     *
     * @return string
     */
    public function getNotice()
    {
        return $this->notice;
    }

    /**
     * Set outfit
     *
     * @param string $outfit
     *
     * @return Workout
     */
    public function setOutfit($outfit)
    {
        $this->outfit = $outfit;

        return $this;
    }

    /**
     * Get outfit
     *
     * @return string
     */
    public function getOutfit()
    {
        return $this->outfit;
    }

    /**
     * Set coach
     *
     * @param \Bemoove\AppBundle\Entity\Coach $coach
     *
     * @return Workout
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
     * Add tag
     *
     * @param \Bemoove\AppBundle\Entity\Tag $tag
     *
     * @return Workout
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
     * Set photo
     *
     * @param \Bemoove\AppBundle\Entity\Image $photo
     *
     * @return Workout
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
     * Set owner
     *
     * @param \Bemoove\AppBundle\Entity\Account $owner
     *
     * @return Workout
     */
    public function setOwner(\Bemoove\AppBundle\Entity\Account $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Bemoove\AppBundle\Entity\Account
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Workout
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }
}
