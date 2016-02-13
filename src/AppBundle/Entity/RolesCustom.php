<?php
namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Table(name="roles_custom")
* @ORM\Entity()
*/
class RolesCustom implements RoleInterface
{
    /**
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     * @Assert\NotBlank()
     */
    private $role;

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
     * @return RolesCustom
     */
    public function setName($name)
    {
        $this->name = $name;
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

    /** @see RoleInterface */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return RolesCustom
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function toArray()
    {
        $entity = array(
            "id" => $this->id,
            "name" => $this->name,
            "role" => $this->role
        );

        return $entity;
    }

    public function hasImage()
    {
        return false;
    }
}
