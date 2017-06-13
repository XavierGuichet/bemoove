<?php

namespace Bemoove\AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Bemoove\AppBundle\Entity\Place\Address;
use Bemoove\AppBundle\Entity\Image;
use Bemoove\AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

final class ConvertImageSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $securityTokenStorage;

    public function __construct(\Swift_Mailer $mailer, TokenStorageInterface $securityTokenStorage)
    {
        $this->mailer = $mailer;
        $this->securityTokenStorage = $securityTokenStorage;
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


        if (!$image instanceof Image || Request::METHOD_POST !== $method) {
            return;
        }

        $fs = new Filesystem();

        $base64_image = $image->getBase64data();
        $data = explode( ',', $base64_image );

        $folder = $image->getUploadRootDir();
        //crée le dossier de destination s'il n'existe pas
        if(!$fs->exists($folder)) {
            try {
                $fs->mkdir($folder, 0755);
            } catch (IOExceptionInterface $e) {
                // TODO : add Logger
                die("An error occurred while creating your directory at ".$e->getPath());
            }
        }

        try {
            $tmpfile = $fs->tempnam($folder."tmp","tmp");
        } catch (IOExceptionInterface $e) {
            // TODO : add Logger
            die("Tmp file creation failed");
        }

        try {
            $fs->dumpFile($tmpfile, base64_decode($data[1]));
        } catch (IOExceptionInterface $e) {
            // TODO : add Logger
            die("file image dumping failed");
        }

        $fileName = md5(uniqid());
        $fileExt = '.jpg';
        try {
            $fs->copy($tmpfile,$folder."/".$fileName.$fileExt);
        } catch (IOExceptionInterface $e) {
            // TODO : add Logger
            die("image copy failed");
        }

        // private $name;
        $image->setName($fileName);
        $image->setPath("http://".$_SERVER['HTTP_HOST']."/uploads/images/".$fileName.$fileExt);
        // private $file;
        // private $kind;
        // private $owner;
        // private $base64data;
        // private $slug_name;
        // die();
    }

}
