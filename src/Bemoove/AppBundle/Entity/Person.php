<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Person.
 *
 * @ApiResource(attributes={
 *          "filters"={"person.account"},
 *          "normalization_context"={"groups"={"person"}},
 *          "denormalization_context"={"groups"={"post_person"}},})
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\PersonRepository")
 */
class Person
{
    /**
     * @var int
     *
     * @Groups({"person"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

     /**
      * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Account", inversedBy="person", cascade={"persist"})
      */
     private $account;

    /**
     * @var string
     * @Groups({"workout","person","post_person"})
     * @ORM\Column(name="LastName", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     * @Groups({"workout","person","post_person"})
     * @ORM\Column(name="FirstName", type="string", length=255, nullable=true)
     */
    private $firstName;

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
     * @ORM\Column(name="Biography", type="text", length=1000, nullable=true)
     */
    private $biography;

    /**
     * @var string
     * @Groups({"workout","person","post_person"})
     * @ORM\Column(name="Birthdate", type="date", nullable=true)
     */
    private $birthdate;

     /**
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
     * @return Person
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Person
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
     * @return Person
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
     * Set biography
     *
     * @param string $biography
     *
     * @return Person
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Get biography
     *
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * Set account
     *
     * @param \Bemoove\AppBundle\Entity\Account $account
     *
     * @return Person
     */
    public function setAccount(\Bemoove\AppBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Bemoove\AppBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set address
     *
     * @param \Bemoove\AppBundle\Entity\Place\Address $address
     *
     * @return Person
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
     * @return Person
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
}
