<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=255, nullable=true)
     */
    private $cover;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="games")
     */
    private $owners;

    /**
     * @ORM\OneToOne(targetEntity="Challenge", inversedBy="game")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     */
    private $challenge;

    /**
     * @ORM\ManyToOne(targetEntity="Editor", inversedBy="games")
     * @ORM\JoinColumn(name="editor_id", referencedColumnName="id")
     */
    private $editor;

    /**
     * @var int
     *
     * @ORM\Column(name="pegi", type="integer")
     */
    private $pegi;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="games")
     * @ORM\JoinTable(name="games_categories")
     */
    private $categories;

    public function __construct()
    {
        $this->owners = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

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
     * @return Game
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

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Game
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
     * Set description
     *
     * @param string $description
     * @return Game
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Game
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set cover
     *
     * @param string $cover
     * @return Game
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string 
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Add owners
     *
     * @param \AppBundle\Entity\User $owners
     * @return Game
     */
    public function addOwner(\AppBundle\Entity\User $owners)
    {
        $this->owners[] = $owners;

        return $this;
    }

    /**
     * Remove owners
     *
     * @param \AppBundle\Entity\User $owners
     */
    public function removeOwner(\AppBundle\Entity\User $owners)
    {
        $this->owners->removeElement($owners);
    }

    /**
     * Get owners
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwners()
    {
        return $this->owners;
    }

    /**
     * Set challenge
     *
     * @param \AppBundle\Entity\Challenge $challenge
     * @return Game
     */
    public function setChallenge(\AppBundle\Entity\Challenge $challenge = null)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * Get challenge
     *
     * @return \AppBundle\Entity\Challenge 
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * Set editor
     *
     * @param \AppBundle\Entity\Editor $editor
     * @return Game
     */
    public function setEditor(\AppBundle\Entity\Editor $editor = null)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor
     *
     * @return \AppBundle\Entity\Editor 
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Set pegi
     *
     * @param integer $pegi
     * @return Game
     */
    public function setPegi($pegi = null)
    {
        $this->pegi = $pegi;

        return $this;
    }

    /**
     * Get pegi
     *
     * @return integer
     */
    public function getPegi()
    {
        return $this->pegi;
    }

    /**
     * Add categories
     *
     * @param \AppBundle\Entity\Category $categories
     * @return Game
     */
    public function addCategory(\AppBundle\Entity\Category $categories)
    {
        $categories->addGame($this);
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \AppBundle\Entity\Category $categories
     */
    public function removeCategory(\AppBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    public function toArray()
    {
        $categoriesArray = array();
        foreach($this->categories as $cat) {
            $categoriesArray[] = $cat->getName();
        }

        $entity = array(
            "id" => $this->id,
            "name" => $this->name,
            "date" => $this->date->format("d/m/Y"),
            "description" => $this->description,
            "rating" => $this->rating,
            "cover" => $this->cover,
            "challenge" => $this->challenge,
            "editeur" => $this->editor,
            "pegi" => $this->pegi,
            "categories" => $categoriesArray
        );

        return $entity;
    }

    public function hasImage()
    {
        return array(
            "get" => $this->getCover(),
            "set" => "setCover"
        );
    }
}
