<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ChallengePosition
 *
 * @ORM\Table(name="challenge_position")
 * @ORM\Entity()
 */
class ChallengePosition
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
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="positions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Challenge")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="id")
     */
    private $challenge;

    /**
     * @ORM\ManyToOne(targetEntity="ChallengeAward")
     * @ORM\JoinColumn(name="award_id", referencedColumnName="id")
     */
    private $award;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function toArray()
    {
        $entity = array(
            "id" => $this->id,
            "position" => $this->position,
            "user" => $this->user->getUsername(),
            "challenge" => $this->challenge->getName()
        );

        return $entity;
    }

    public function hasImage()
    {
        return false;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return ChallengePosition
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return ChallengePosition
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set challenge
     *
     * @param \AppBundle\Entity\Challenge $challenge
     * @return ChallengePosition
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
     * Set award
     *
     * @param \AppBundle\Entity\ChallengeAward $award
     * @return ChallengePosition
     */
    public function setAward(\AppBundle\Entity\ChallengeAward $award = null)
    {
        $this->award = $award;

        return $this;
    }

    /**
     * Get awards
     *
     * @return \AppBundle\Entity\ChallengeAward
     */
    public function getAward()
    {
        return $this->award;
    }
}
