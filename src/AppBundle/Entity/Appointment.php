<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="appointment")
 */
class Appointment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;
    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $isBooked;
    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $isLocked;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientName;

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
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     *
     * @return Appointment
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set isBooked
     *
     * @param boolean $isBooked
     *
     * @return Appointment
     */
    public function setIsBooked($isBooked)
    {
        $this->isBooked = $isBooked;

        return $this;
    }

    /**
     * Get isBooked
     *
     * @return boolean
     */
    public function getIsBooked()
    {
        return $this->isBooked;
    }

    /**
     * Set isLocked
     *
     * @param boolean $isLocked
     *
     * @return Appointment
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    /**
     * Get isLocked
     *
     * @return boolean
     */
    public function getIsLocked()
    {
        return $this->isLocked;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     *
     * @return Appointment
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }
}
