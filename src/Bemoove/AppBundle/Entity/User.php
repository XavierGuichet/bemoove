<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Bemoove\UserBundle\Entity\BaseUser as BaseUser;

/**
 * Sporty
 *
 * @ApiResource
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\OneToOne(targetEntity="Bemoove\AppBundle\Entity\Profile", mappedBy="user", cascade={"persist", "merge"}))
     */
    private $profile;


    /**
     * Set profile
     *
     * @param \Bemoove\AppBundle\Entity\Profile $profile
     *
     * @return User
     */
    public function setProfile(\Bemoove\AppBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Bemoove\AppBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
