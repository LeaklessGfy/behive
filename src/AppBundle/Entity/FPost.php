<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FPost
 *
 * @ORM\Table(name="f_post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FPostRepository")
 */
class FPost
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="FSubject", inversedBy="posts")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    private $subject;


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
     * Set content
     *
     * @param string $content
     * @return FPost
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
     * Set subject
     *
     * @param \AppBundle\Entity\FSubject $subject
     * @return FPost
     */
    public function setSubject(\AppBundle\Entity\FSubject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \AppBundle\Entity\FSubject 
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
