<?php

namespace Bemoove\AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Image.
 *
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"image","workout","profile"}},
 *          "denormalization_context"={"groups"={"post_image"}},
 * })
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Bemoove\AppBundle\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @Groups({"image","workout","profile"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     * @Groups({"workout","profile"})
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/gif", "image/png", "image/jpeg"},
     *     mimeTypesMessage = "Please upload a valid JPEG, PNG or GIF"
     * )
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="kind", type="string", length=255, nullable=true)
     */
    private $kind;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Bemoove\AppBundle\Entity\Account")
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;

    /**
     * @var string
     * @Groups({"post_image","image"})
     */
    private $base64data;

    private $slug_name;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    // TODO : this has been changed to public, but it was protected
    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images/'.$this->getKind();
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        if (empty($this->getKind())) {
            throw new HttpException(404, 'Image Kind must be set');
        }

        $slug_name_ext = $this->slug_name.'.'.$this->getFile()->guessExtension();

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $slug_name_ext
        );

        // set the path property to the filename where you've saved the file
        $this->path = $slug_name_ext;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }


    /**
     * Set kind
     *
     * @param string $kind
     *
     * @return Image
     */
    public function setKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * Get kind
     *
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * Set owner
     *
     * @param \Bemoove\AppBundle\Entity\Account $owner
     *
     * @return Image
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
     * Set kind
     *
     * @param string $kind
     *
     * @return Image
     */
    public function setBase64data($base64data)
    {
        $this->base64data = $base64data;

        return $this;
    }

    /**
     * Get kind
     *
     * @return string
     */
    public function getBase64data()
    {
        return $this->base64data;
    }
}
