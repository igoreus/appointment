<?php

namespace AppBundle\Domain\Model;

class Appointment
{
    /** @var int */
    private $id;
    /** @var \DateTime */
    private $dateTime;
    /** @var bool */
    private $isBooked;
    /** @var bool */
    private $isLocked;
    /** @var string */
    private $clientName;

    /**
     * @param \DateTime $dateTime
     * @return Appointment
     */
    public static function createNew(\DateTime $dateTime)
    {
        return new self(null, $dateTime, 0, 0, null);
    }

    /**
     * @param int $id
     * @param \DateTime $dateTime
     * @param bool $isBooked
     * @param bool $isLocked
     * @param string $clientName
     */
    public function __construct($id, \DateTime $dateTime, $isBooked, $isLocked, $clientName)
    {
        $this->id = (int) $id;
        $this->dateTime = clone $dateTime;
        $this->isBooked = (boolean) $isBooked;
        $this->isLocked = (boolean) $isLocked;
        $this->clientName = (string) $clientName;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @param boolean $isBooked
     */
    public function setIsBooked($isBooked)
    {
        $this->isBooked = $isBooked;
    }

    /**
     * @param boolean $isLocked
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;
    }

    /**
     * @param string $clientName
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return clone $this->dateTime;
    }

    /**
     * @return boolean
     */
    public function getIsBooked()
    {
        return $this->isBooked;
    }

    /**
     * @return boolean
     */
    public function getIsLocked()
    {
        return $this->isLocked;
    }

    /**
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }
}
