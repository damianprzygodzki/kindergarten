<?php

namespace KindergartenApiBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;


/**
 * @ORM\Entity
 * @ORM\Table(name="FosUser")
 *
 * @JMS\ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="sender")
     */
    private $messagesSent;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="receiver")
     * @ORM\OrderBy({"received" = "ASC", "datetime" = "DESC"})
     */
    private $messagesReceived;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255)
     */
    private $fullname;

    /**
     * @ORM\OneToMany(targetEntity="Child", mappedBy="childParent")
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="Classroom", mappedBy="teacher")
     */
    private $classroom;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users")
     * @ORM\JoinColumn(name="groupId", referencedColumnName="id")
     */
    private $group;


    public function __construct()
    {
        parent::__construct();
        $this->messagesSent = new ArrayCollection();
        $this->messagesReceived = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->classroom = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMessagesSent()
    {
        return $this->messagesSent;
    }

    /**
     * @param mixed $messagesSent
     */
    public function setMessagesSent($messagesSent)
    {
        $this->messagesSent = $messagesSent;
    }

    /**
     * @return mixed
     */
    public function getMessagesReceived()
    {
        return $this->messagesReceived;
    }

    /**
     * @param mixed $messagesReceived
     */
    public function setMessagesReceived($messagesReceived)
    {
        $this->messagesReceived = $messagesReceived;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClassroom()
    {
        return $this->classroom;
    }

    /**
     * @param mixed $classroom
     */
    public function setClassroom($classroom)
    {
        $this->classroom = $classroom;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }
}