<?php

namespace Greg\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Advert
 *
 * @ORM\Table(name="greg_advert")
 * @ORM\Entity(repositoryClass="Greg\PlatformBundle\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity="Greg\PlatformBundle\Entity\Image", cascade={"persist"})
     */

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * @ORM\OneToOne(targetEntity="Greg\PlatformBundle\Entity\Image", cascade={"persist")}
     */
    private $image;

    /**
     * @ORM\OneToOne(targetEntity="Greg\PlatformBundle\Entity\Image", cascade={"persist"})
     * @ORM\ManyToMany(targetEntity="Greg\PlatformBundle\Entity\Category", cascade={"persist"})
     * @ORM\JoinTable(name="greg_advert_category")
     */
    private $categories;

    /**
     *
     * @ORM\OneToMany(targetEntity="Greg\PlatformBundle\Entity\Application", mappedBy="advert")
     */
    private $applications;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="nb_applications", type="integer")
     */
    private $nbApplications = 0;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;


    public function __construct()
    {
        $this->date         = new \DateTime();
        $this->categories   = new ArrayCollection();
        $this->applications = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    public function increaseApplication()
    {
        $this->nbApplications++;
    }

    public function decreaseApplication()
    {
        $this->nbApplications--;
    }


    // Getters et Setters
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set image
     *
     * @param \Greg\PlatformBundle\Entity\Image $image
     *
     * @return Advert
     */
    public function setImage(\Greg\PlatformBundle\Entity\Image $image = null)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return \Greg\PlatformBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }

    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }


    public function addApplication(Application $application)
    {
        $this->applications[] = $application;

        // liaison entre l'annonce et la candidature
        $application->setAdvert($this);
        return $this;
    }

    public function removeApplication(Application $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * @return mixed
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\Datetime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param integer $nbApplications
     */
    public function setNbApplications($nbApplications)
    {
        $this->nbApplications = $nbApplications;
    }

    /**
     * @return integer
     */
    public function getNbApplications()
    {
        return $this->nbApplications;
    }

    /**
     * @param string $slug
     */

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
