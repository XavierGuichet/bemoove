<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingSession
 *
 * @ApiResource(attributes={
 *          "denormalization_context"={"groups"={"post_workout"}},
 *          "normalization_context"={"groups"={"workout"}}
 *  })
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\TagRepository")
 */
class Tag
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
     * @var string
     *
     * @Groups({"workout","post_workout"})
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * Set name
     *
     * @param string $name
     *
     * @return Tag
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
}
