<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Profile
 *
 * @ApiResource(attributes={
 *                        "filters"={"profile.search"},
 *                        "normalization_context"={"groups"={"profile"}},})
 * @ORM\Table(name="profile")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\ProfileRepository")
 */
class Profile
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Groups({"profile"})
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @Groups({"profile"})
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @Groups({"profile"})
     * @ORM\Column(name="telHome", type="string", length=255, nullable=true)
     */
    private $telHome;

    /**
     * @var string
     *
     * @Groups({"profile"})
     * @ORM\Column(name="TelMobile", type="string", length=255, nullable=true)
     */
    private $telMobile;

    /**
     * @Groups({"profile"})
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Place\Address", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
     private $address;

    /**
     * @var string
     *
     * @Groups({"profile"})
     * @ORM\Column(name="Ismale", type="boolean", nullable=true)
     */
    private $ismale;

    /**
     * @var string
     *
     * @Groups({"profile"})
     * @ORM\Column(name="presentation", type="text", nullable=true)
     */
    private $presentation;

    /**
     * @var \DateTime
     *
     * @Groups({"profile"})
     * @ORM\Column(name="Birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Account", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @var string
     *
     * @Groups({"profile"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $photo;


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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Profile
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Profile
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set telHome
     *
     * @param string $telHome
     *
     * @return Profile
     */
    public function setTelHome($telHome)
    {
        $this->telHome = $telHome;

        return $this;
    }

    /**
     * Get telHome
     *
     * @return string
     */
    public function getTelHome()
    {
        return $this->telHome;
    }

    /**
     * Set telMobile
     *
     * @param string $telMobile
     *
     * @return Profile
     */
    public function setTelMobile($telMobile)
    {
        $this->telMobile = $telMobile;

        return $this;
    }

    /**
     * Get telMobile
     *
     * @return string
     */
    public function getTelMobile()
    {
        return $this->telMobile;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Profile
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set ismale
     *
     * @param string $ismale
     *
     * @return Profile
     */
    public function setIsmale($ismale)
    {
        $this->ismale = $ismale;

        return $this;
    }

    /**
     * Get ismale
     *
     * @return string
     */
    public function getIsmale()
    {
        return $this->ismale;
    }

    /**
     * Set presentation
     *
     * @param string $presentation
     *
     * @return Profile
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Profile
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set owner
     *
     * @param string $owner
     *
     * @return Profile
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Profile
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
