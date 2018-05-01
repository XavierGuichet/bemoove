<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Bemoove\AppBundle\Entity\Place\Address;
use Bemoove\AppBundle\Entity\Image;
use Bemoove\AppBundle\Entity\User;

final class ConvertImageSubscriber implements EventSubscriberInterface
{
    const FILE_EXT = '.jpg';
    private $fs;

    public function __construct()
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['base64ToFile', EventPriorities::PRE_VALIDATE]],
        ];
    }

    //Transforme l'image en base64 en fichier
    public function base64ToFile(GetResponseForControllerResultEvent $event)
    {
        $image = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$image instanceof Image || ( Request::METHOD_POST !== $method && Request::METHOD_PUT !== $method )) {
            return;
        }

        $this->fs = new Filesystem();
        $tmpFile = $this->createTmpImage($image);

        if (Request::METHOD_POST === $method) {
            $this->createNewImage($image, $tmpFile);
        }

        if (Request::METHOD_PUT === $method) {
            $folder = $image->getUploadRootDir();
            $old_image = $folder."/".$image->getName().self::FILE_EXT;

            $this->createNewImage($image, $tmpFile);
            $this->fs->remove($old_image);
        }

        return;
    }

    private function createTmpImage($image) {
        $folder = $image->getUploadRootDir();
        $base64_image = $image->getBase64data();
        $data = explode( ',', $base64_image );

        try {
            $tmpfile = $this->fs->tempnam($folder."tmp","tmp");
        } catch (IOExceptionInterface $e) {
            // TODO : add Logger
            throw new \Exception("Tmp file creation failed", 1);
        }

        try {
            $this->fs->dumpFile($tmpfile, base64_decode($data[1]));
        } catch (IOExceptionInterface $e) {
            // TODO : add Logger
            throw new \Exception("file image dumping failed", 1);
        }

        return $tmpfile;
    }

    private function createNewImage($image, $tmpFile) {
        $folder = $image->getUploadRootDir();
        //crée le dossier de destination s'il n'existe pas
        if(!$this->fs->exists($folder)) {
            try {
                $this->fs->mkdir($folder, 0755);
            } catch (IOExceptionInterface $e) {
                // TODO : add Logger
                throw new \Exception("An error occurred while creating your directory at ".$e->getPath(), 1);
            }
        }

        $fileName = md5(uniqid());
        try {
            $this->fs->copy($tmpFile,$folder."/".$fileName.self::FILE_EXT);
        } catch (IOExceptionInterface $e) {
            // TODO : add Logger
            die("image copy failed : ".$e->getPath());
        }

        // private $name;
        $image->setName($fileName);
        $image->setPath("https://".$_SERVER['HTTP_HOST']."/uploads/images/".$fileName.self::FILE_EXT);
    }
}
