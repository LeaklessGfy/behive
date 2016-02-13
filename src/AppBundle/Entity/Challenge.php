<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Challenge
 *
 * @ORM\Table(name="challenge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChallengeRepository")
 */
class Challenge
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
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=255, nullable=true)
     */
    private $cover;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="challenges")
     */
    private $players;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="challenge")
     */
    private $game;

    /**
     * @ORM\OneToMany(targetEntity="ChallengeLimit", mappedBy="challenge", cascade={"persist"}, orphanRemoval=true)
     */
    private $limits;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_daily", type="boolean")
     */
    private $isDaily;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->limits = new ArrayCollection();
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
     * @return Challenge
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
     * Set cover
     *
     * @param string $cover
     * @return Challenge
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
     * Set description
     *
     * @param string $description
     * @return Challenge
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
     * Add players
     *
     * @param \AppBundle\Entity\User $players
     * @return Challenge
     */
    public function addPlayer(\AppBundle\Entity\User $players)
    {
        $this->players[] = $players;

        return $this;
    }

    /**
     * Remove players
     *
     * @param \AppBundle\Entity\User $players
     */
    public function removePlayer(\AppBundle\Entity\User $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Set game
     *
     * @param \AppBundle\Entity\Game $game
     * @return Challenge
     */
    public function setGame(\AppBundle\Entity\Game $game = null)
    {
        $game->addChallenge($this);
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \AppBundle\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Add limits
     *
     * @param \AppBundle\Entity\ChallengeLimit $limits
     * @return Challenge
     */
    public function addLimit(\AppBundle\Entity\ChallengeLimit $limits)
    {
        $this->limits[] = $limits;

        return $this;
    }

    /**
     * Remove limits
     *
     * @param \AppBundle\Entity\ChallengeLimit $limits
     */
    public function removeLimit(\AppBundle\Entity\ChallengeLimit $limits)
    {
        $this->limits->removeElement($limits);
        $limits->setChallenge(null);
    }

    /**
     * Get limits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLimits()
    {
        return $this->limits;
    }

    public function toArray()
    {
        $playersArray = array();
        foreach($this->players as $player) {
            $playersArray[] = $player->getUsername();
        }

        $limitsArray = array();
        foreach($this->limits as $limit) {
            $limitsArray[] = $limit->getBegin() . " - " . $limit->getEnd() . " - " . $limit->getPoints() . " points";
        }

        $entity = array(
            "id" => $this->id,
            "name" => $this->name,
            "cover" => $this->cover,
            "description" => $this->description,
            "players" => $playersArray,
            "game" => $this->game ? $this->game->getName() : null,
            "limits" => $limitsArray
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

    /**
     * Set isDaily
     *
     * @param boolean $isDaily
     * @return Challenge
     */
    public function setIsDaily($isDaily)
    {
        $this->isDaily = $isDaily;

        return $this;
    }

    /**
     * Get isDaily
     *
     * @return boolean 
     */
    public function getIsDaily()
    {
        return $this->isDaily;
    }
}
