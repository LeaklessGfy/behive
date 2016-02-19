<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $password;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $level;

    /**
     * @var float
     *
     * @ORM\Column(name="xp", type="float")
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     */
    private $xp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_connexion", type="datetime", nullable=true)
     */
    private $lastConnexion;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champs"
     * )
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas valide",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     * @Assert\Image(
     *      maxSize = "200k",
     *      mimeTypesMessage = "Veuillez uploader une image valide"
     * )
     */
    private $avatar;

    /**
     * @ORM\ManyToMany(targetEntity="Badge", inversedBy="users")
     * @ORM\JoinTable(name="users_badges")
     */
    private $badges;

    /**
     * @ORM\ManyToMany(targetEntity="Game", inversedBy="owners")
     * @ORM\JoinTable(name="owners_games")
     */
    private $games;

    /**
     * @ORM\ManyToMany(targetEntity="Challenge", inversedBy="players")
     * @ORM\JoinTable(name="players_challenges")
     */
    private $challenges;

    /**
     * @ORM\ManyToOne(targetEntity="RolesCustom")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $roles;

    public function __construct() {
        $this->badges = new ArrayCollection();
        $this->myGames = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->challenges = new ArrayCollection();
        $this->xp = 10;
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return User
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set xp
     *
     * @param float $xp
     * @return User
     */
    public function setXp($xp)
    {
        $this->xp = $xp;

        return $this;
    }

    /**
     * Get xp
     *
     * @return float 
     */
    public function getXp()
    {
        return $this->xp;
    }

    /**
     * Set lastConnexion
     *
     * @param \DateTime $lastConnexion
     * @return User
     */
    public function setLastConnexion($lastConnexion)
    {
        $this->lastConnexion = $lastConnexion;

        return $this;
    }

    /**
     * Get lastConnexion
     *
     * @return \DateTime 
     */
    public function getLastConnexion()
    {
        return $this->lastConnexion;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add badges
     *
     * @param \AppBundle\Entity\Badge $badges
     * @return User
     */
    public function addBadge(\AppBundle\Entity\Badge $badges)
    {
        $this->badges[] = $badges;

        return $this;
    }

    /**
     * Remove badges
     *
     * @param \AppBundle\Entity\Badge $badges
     */
    public function removeBadge(\AppBundle\Entity\Badge $badges)
    {
        $this->badges->removeElement($badges);
    }

    /**
     * Get badges
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBadges()
    {
        return $this->badges;
    }

    /**
     * Add games
     *
     * @param \AppBundle\Entity\Game $games
     * @return User
     */
    public function addGame(\AppBundle\Entity\Game $games)
    {
        $this->games[] = $games;

        return $this;
    }

    /**
     * Remove games
     *
     * @param \AppBundle\Entity\Game $games
     */
    public function removeGame(\AppBundle\Entity\Game $games)
    {
        $this->games->removeElement($games);
    }

    /**
     * Get games
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Add challenges
     *
     * @param \AppBundle\Entity\Challenge $challenges
     * @return User
     */
    public function addChallenge(\AppBundle\Entity\Challenge $challenges)
    {
        $this->challenges[] = $challenges;

        return $this;
    }

    /**
     * Remove challenges
     *
     * @param \AppBundle\Entity\Challenge $challenges
     */
    public function removeChallenge(\AppBundle\Entity\Challenge $challenges)
    {
        $this->challenges->removeElement($challenges);
    }

    /**
     * Get challenges
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChallenges()
    {
        return $this->challenges;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        $role = $this->roles;

        if(count($role) > 0) {
            return array($role->getRole());
        }

        return $this->roles;
    }

    public function setRoles($roles) {
        $this->roles = $roles;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }

    public function eraseCredentials()
    {
    }

    public function toArray() {
        $badgesArray = array();
        foreach($this->badges as $badge) {
            $badgesArray[] = $badge->getName();
        }

        $gamesArray = array();
        foreach($this->games as $game) {
            $gamesArray[] = $game->getName();
        }

        $challengesArray = array();
        foreach($this->challenges as $challenge) {
            $challengesArray[] = $challenge->getName();
        }

        $entity = array(
            "id" => $this->id,
            "username" => $this->username,
            "dernière connexion" => $this->lastConnexion->format("d/m/Y H:i:s"),
            "role" => $this->roles ? $this->roles->getRole() : null,
            "level" => $this->level,
            "xp" => $this->xp,
            "avatar" => $this->avatar,
            "badges" => $badgesArray,
            "games" => $gamesArray,
            "challenges" => $challengesArray
        );

        return $entity;
    }

    public function hasImage()
    {
        return array(
            "get" => $this->getAvatar(),
            "set" => "setAvatar"
        );
    }
}
