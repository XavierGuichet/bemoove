<?php

namespace Bemoove\AdminBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Bemoove\UserBundle\Entity\BaseUser as BaseUser;

/**
 * Admin
 *
 * @ApiResource
 * @ORM\Table(name="admin")
 * @ORM\Entity(repositoryClass="Bemoove\AdminBundle\Repository\AdminRepository")
 */
class Admin extends BaseUser
{

}
