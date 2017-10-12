<?php

namespace Bemoove\AppBundle\Entity\Place;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Address
 *
 * @ApiResource(attributes={"filters"={"address.search"},
 * "normalization_context"={"groups"={"address","business","workout","bankAccount","post_bankAccount"}},
 * })
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\Place\AddressRepository")
 */
class Address
{
    /**
     * @var int
     *
     * @Groups({"address","business","workout","bankAccount"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     * @Groups({"address","business","workout","post_workout"})
     * @ORM\Column(name="isWorkoutLocation", type="boolean", nullable=true)
     */
    private $isWorkoutLocation;

    /**
     * @var string
     * @Groups({"address","business","workout","post_workout"})
     * @ORM\Column(name="Name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     * @Groups({"address","business","workout","post_workout","bankAccount"})
     * @ORM\Column(name="Firstline", type="string", length=255, nullable=true)
     */
    private $firstline;

    /**
     * @var string
     * @Groups({"post_workout","bankAccount","address","business","workout"})
     * @ORM\Column(name="Secondline", type="string", length=255, nullable=true)
     */
    private $secondline;

    /**
     * @Groups({"address","business","workout","bankAccount"})
     * @ORM\Column(name="City", type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"address"})
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="PostalCode", type="string", length=255, nullable=true)
     * @Groups({"address","business","workout","bankAccount"})
     * @Assert\NotBlank(groups={"address"})
     * @Assert\Length(min=5,groups={"address"})
     * @Assert\Length(max=5,groups={"address"})
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="Latitude", type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @Groups({"address","business","workout"})
     * @ORM\Column(name="Longitude", type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Editable", type="boolean",nullable=true)
     */
    private $editable;


    /**
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Account", cascade={"persist"})
     * @Groups({"address","business","workout"})
     * @Assert\NotNull(groups={"address"})
     * @Assert\NotBlank(groups={"address"})
     */
    private $owner;



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
     * Set firstline
     *
     * @param string $firstline
     *
     * @return Address
     */
    public function setFirstline($firstline)
    {
        $this->firstline = $firstline;

        return $this;
    }

    /**
     * Get firstline
     *
     * @return string
     */
    public function getFirstline()
    {
        return $this->firstline;
    }

    /**
     * Set secondline
     *
     * @param string $secondline
     *
     * @return Address
     */
    public function setSecondline($secondline)
    {
        $this->secondline = $secondline;

        return $this;
    }

    /**
     * Get secondline
     *
     * @return string
     */
    public function getSecondline()
    {
        return $this->secondline;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Address
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Address
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Address
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set editable
     *
     * @param string $editable
     *
     * @return Address
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Get editable
     *
     * @return string
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Address
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set creator
     *
     * @param \Bemoove\AppBundle\Entity\Account $creator
     *
     * @return Address
     */
    public function setCreator(\Bemoove\AppBundle\Entity\Account $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Bemoove\AppBundle\Entity\Account
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set owner
     *
     * @param \Bemoove\AppBundle\Entity\Account $owner
     *
     * @return Address
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
     * Set isWorkoutLocation
     *
     * @param boolean $isWorkoutLocation
     *
     * @return Address
     */
    public function setIsWorkoutLocation($isWorkoutLocation)
    {
        $this->isWorkoutLocation = $isWorkoutLocation;

        return $this;
    }

    /**
     * Get isWorkoutLocation
     *
     * @return boolean
     */
    public function getIsWorkoutLocation()
    {
        return $this->isWorkoutLocation;
    }
}
