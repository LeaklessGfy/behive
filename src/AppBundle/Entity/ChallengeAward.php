<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ChallengeAward
 *
 * @ORM\Table(name="challenge_award")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChallengeAwardRepository")
 */
class ChallengeAward
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
     * @ORM\Column(name="name", type="string", length=40)
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $points;

    /**
     * @ORM\OneToMany(targetEntity="ChallengeLimit", mappedBy="award")
     */
    private $limits;

    /**
     * @ORM\ManyToOne(targetEntity="Badge")
     * @ORM\JoinColumn(name="badge_id", referencedColumnName="id")
     */
    private $badge;

    /**
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;


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
     * Set points
     *
     * @param integer $points
     * @return ChallengeAward
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ChallengeAward
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
     * Constructor
     */
    public function __construct()
    {
        $this->limits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add limits
     *
     * @param \AppBundle\Entity\ChallengeLimit $limits
     * @return ChallengeAward
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
        $entity = array(
            "id" => $this->id,
            "name" => $this->name,
            "points" => $this->points
        );

        return $entity;
    }

    public function hasImage()
    {
        return false;
    }

    /**
     * Set badge
     *
     * @param \AppBundle\Entity\Badge $badge
     * @return ChallengeAward
     */
    public function setBadge(\AppBundle\Entity\Badge $badge = null)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get badge
     *
     * @return \AppBundle\Entity\Badge 
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Set game
     *
     * @param \AppBundle\Entity\Game $game
     * @return ChallengeAward
     */
    public function setGame(\AppBundle\Entity\Game $game = null)
    {
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
}
