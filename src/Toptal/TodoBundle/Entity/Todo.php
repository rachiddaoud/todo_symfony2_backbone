<?php

namespace Toptal\TodoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Todo
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity
 */
class Todo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duedate", type="date")
     */
    private $duedate;

    /**
     * @var integer
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 2,
     *      minMessage = "The priority should be between 0 and 2.",
     *      maxMessage = "The priority should be between 0 and 2."
     * )
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;

    /**
     * @var boolean
     *
     * @ORM\Column(name="completed", type="boolean")
     */
    private $completed;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     *
     * @var Commande $user
     */
    private $user;

    /**
     * @ORM\prePersist
     */
    public function setCreatedValue()
    {
        $this->duedate = new \DateTime();
        $this->priority = 1;
        $this->completed = false;
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
     * Set title
     *
     * @param string $title
     * @return Todo
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
     * Set duedate
     *
     * @param \DateTime $duedate
     * @return Todo
     */
    public function setDuedate($duedate)
    {
        $this->duedate = $duedate;
    
        return $this;
    }

    /**
     * Get duedate
     *
     * @return \DateTime 
     */
    public function getDuedate()
    {
        return $this->duedate;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Todo
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    
        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     * @return Todo
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    
        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set user
     *
     * @param \Toptal\TodoBundle\Entity\User $user
     * @return Todo
     */
    public function setUser(\Toptal\TodoBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Toptal\TodoBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}