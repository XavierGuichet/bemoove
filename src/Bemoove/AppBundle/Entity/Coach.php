<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Coach.
 *
 * @ApiResource(attributes={
 *          "filters"={"coach.search"},
 *          "normalization_context"={"groups"={"person","image"}},
 *          "denormalization_context"={"groups"={"post_person"}},})
 * @ORM\Table(name="coach")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\CoachRepository")
 */
class Coach
{
    /**
     * @var int
     *
     * @Groups({"person","business","partial_coach"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Groups({"person","post_person","business_valid"})
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Business", inversedBy="coaches")
     */
    private $business;

    /**
     * @var string
     * @Groups({"workout","business","person","post_person","partial_coach"})
     * @ORM\Column(name="LastName", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     * @Groups({"workout","business","person","post_person","partial_coach"})
     * @ORM\Column(name="FirstName", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     * @Groups({"workout","business","person","post_person"})
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @Groups({"workout","person","post_person"})
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Place\Address")
     * @ORM\JoinColumn(nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @Groups({"workout","person","post_person"})
     * @ORM\Column(name="PhoneNumber", type="string", length=255, nullable=true)
     * @Assert\Regex("/^(0|\\+33|0033)[1-9][0-9]{8}?$/i")
     */
    private $phoneNumber;

    /**
     * @var bool
     *
     * @Groups({"workout","person","post_person"})
     * @ORM\Column(name="Ismale", type="boolean", nullable=true)
     */
    private $ismale;

    /**
     * @var string
     * @Groups({"workout","person","post_person"})
     * @ORM\Column(name="Description", type="text", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var string
     * @Groups({"workout","business","person","post_person"})
     * @ORM\Column(name="Birthdate", type="date", nullable=true)
     */
    private $birthdate;

     /**
      * @Groups({"workout","business","person","post_person","partial_coach"})
      * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Image", cascade={"persist"})
      * @ORM\JoinColumn(nullable=true)
      */
     private $photo;

      /**
       * @var string
       * @Groups({"workout","person","post_person"})
       * @ORM\Column(name="CountryOfResidence", type="date", nullable=true)
       */
      private $countryOfResidence;

      /**
       * @var string
       * @Groups({"workout","person","post_person"})
       * @ORM\Column(name="Nationality", type="date", nullable=true)
       */
      private $nationality;

    /**
     * Set business
     *
     * @param \Bemoove\AppBundle\Entity\Business $business
     *
     * @return Coach
     */
    public function setBusiness(\Bemoove\AppBundle\Entity\Business $business = null)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return \Bemoove\AppBundle\Entity\Business
     */
    public function getBusiness()
    {
        return $this->business;
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
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Coach
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
     * @return Coach
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
     * Set email
     *
     * @param string $email
     *
     * @return Coach
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Coach
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set ismale
     *
     * @param boolean $ismale
     *
     * @return Coach
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
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Coach
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
     * Set countryOfResidence
     *
     * @param \DateTime $countryOfResidence
     *
     * @return Coach
     */
    public function setCountryOfResidence($countryOfResidence)
    {
        $this->countryOfResidence = $countryOfResidence;

        return $this;
    }

    /**
     * Get countryOfResidence
     *
     * @return \DateTime
     */
    public function getCountryOfResidence()
    {
        return $this->countryOfResidence;
    }

    /**
     * Set nationality
     *
     * @param \DateTime $nationality
     *
     * @return Coach
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return \DateTime
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set address
     *
     * @param \Bemoove\AppBundle\Entity\Place\Address $address
     *
     * @return Coach
     */
    public function setAddress(\Bemoove\AppBundle\Entity\Place\Address $address = null)
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
     * Set photo
     *
     * @param \Bemoove\AppBundle\Entity\Image $photo
     *
     * @return Coach
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
     * Set description
     *
     * @param string $description
     *
     * @return Coach
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
}
