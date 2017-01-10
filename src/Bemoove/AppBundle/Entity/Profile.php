<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ApiResource
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
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\User", inversedBy="profile", cascade={"persist"})
     */
     private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Place\Address", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="Tel_home", type="string", length=255)
     */
    private $telHome;

    /**
     * @var string
     *
     * @ORM\Column(name="Tel_mobile", type="string", length=255)
     */
    private $telMobile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ismale", type="boolean")
     */
    private $ismale;

    /**
     * @var string
     * @ORM\Column(name="Presentation", type="text", length=1000, nullable=true)
     */
    private $presentation;

    /**
     * @var string
     * @ORM\Column(name="Birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var string
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
     private $avatar;


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
     * Set user
     *
     * @param \Bemoove\AppBundle\Entity\User $user
     *
     * @return Profile
     */
    public function setUser(\Bemoove\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Bemoove\AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set address
     *
     * @param \Bemoove\AppBundle\Entity\Place\Address $address
     *
     * @return Profile
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
     * Set ismale
     *
     * @param boolean $ismale
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
     * @return boolean
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
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Profile
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set avatar
     *
     * @param \Bemoove\AppBundle\Entity\Image avatar
     *
     * @return Profile
     */
    public function setAvatar(\Bemoove\AppBundle\Entity\Image $avatar = null)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Bemoove\AppBundle\Entity\Place\Image
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
