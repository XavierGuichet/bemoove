<?php

namespace Bemoove\AppBundle\Entity\Place;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ApiResource
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\AddressRepository")
 */
class Address
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
     * @ORM\Column(name="Firstline", type="string", length=255)
     */
    private $firstline;

    /**
     * @var string
     *
     * @ORM\Column(name="Secondline", type="string", length=255, nullable=true)
     */
    private $secondline;

    /**
     * @var string
     *
     * @ORM\Column(name="District", type="string", length=255)
     */
    private $district;

    /**
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Place\City", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="Latitude", type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="Longitude", type="string", length=255, nullable=true)
     */
    private $longitude;




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
     * Set district
     *
     * @param string $district
     *
     * @return Address
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
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
}
