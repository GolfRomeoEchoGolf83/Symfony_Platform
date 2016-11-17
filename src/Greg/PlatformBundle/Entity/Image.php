<?php

namespace Greg\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="greg_image")
 * @ORM\Entity(repositoryClass="Greg\PlatformBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    private $file;

    private $tempFilename;

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // est-ce qu'il y a déjà un fichier pour cette entité ?
        if (null !== $this->url) {
            // sauvegarde l'extension du fichier pour le suppirmer plus tard
            $this->tempFilename = $this->url;
            // réinitialise les valeurs des attributs url et alt
            $this->url = null;
            $this->alt = null;
        }
    }

    public function preUpload()
    {
        // s'il n'y a pas de fichier on ne fait rien
        if (null === $this->file) {
            return;
        }
        // stocke l'extension
        $this->url = $this->file->guessExtension();
        // génère alt de <img>
        $this->alt = $this->file->getClientOriginalName();
    }

    public function upload()
    {
        // s'il n'y a pas de fichier
        if (null === $this->file) {
            return;
        }

        // si on avait un ancien fichier on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->getUploadRootDir(), // répertoire de destination
            $this->id.'.'.$this->url // nom du fichier à créer "id.extension"
        );

    }

    public function getUploadRootDir()
    {
        // récupère le chemin relatif vers l'image pour le code php
        return __DIR__.'/../../../../web/'.$this->getUploardDir();
    }

    public function getUploardDir()
    {
        // retourne le chemin relatif (au répertoire /web) vers l'image
        return 'uploads/img';
    }

    /**
     * @return string
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // sauvegarde temporairement le nom du fichier
        $this->tempFilename = $this->getUploadRootDir(). '/' .$this->id. '/' .$this->url;
    }

    /**
     * @return string
     * @ORM\PostRemove()
     */
    public function removeUpload
    {
        // on n'a pas accès à l'id en PostRemove donc utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // supprime le fichier
            unlink($this->tempFilename);
        }
    }

    public function getWebPath()
    {
        return $this->getUploadRootDir(). '/' .$this->getId(). '.' .$this->getUrl();
    }

    /**
     * Get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }
}
