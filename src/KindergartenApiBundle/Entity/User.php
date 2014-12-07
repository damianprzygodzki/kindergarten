<?php

namespace KindergartenApiBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *
 * @ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="sender")
     */
    protected $messagesSent;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="receiver")
     */
    protected $messagesReceived;




    public function __construct()
    {
        parent::__construct();
        $this->messagesSent = new ArrayCollection();
        $this->messagesReceived = new ArrayCollection();
        $this->children = new ArrayCollection();
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
}