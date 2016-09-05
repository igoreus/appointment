<?php

namespace AppBundle\Domain\Command;

use AppBundle\Domain\Validate\GenerateSlotsValidator;

class GenerateSlots extends AbstractCommand
{
    /** @var  \DateTime */
    private $datetime;
    /** @var string  */
    private $fromTime;
    /** @var string  */
    private $tillTime;
    /** @var int */
    private $interval;

    /**
     * @param GenerateSlotsValidator $validator
     * @param string $datetime
     * @param string $fromTime
     * @param string $tillTime
     * @param int $interval
     */
    public function __construct(GenerateSlotsValidator $validator, $datetime, $fromTime, $tillTime, $interval)
    {
        $this->datetime = new \DateTime($datetime);
        $this->fromTime = $fromTime;
        $this->tillTime = $tillTime;
        $this->interval = (int) $interval;
        $this->setValidator($validator);
    }

    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return clone $this->datetime;
    }

    /**
     * @return string
     */
    public function getFromTime()
    {
        return $this->fromTime;
    }

    /**
     * @return string
     */
    public function getFromTimeHours()
    {
        return $this->getHoursFromString($this->getFromTime());
    }

    /**
     * @return string
     */
    public function getFromTimeMinutes()
    {
        return $this->getMinutesFromString($this->getFromTime());
    }

    /**
     * @return string
     */
    public function getTillTimeHours()
    {
        return $this->getHoursFromString($this->getTillTime());
    }

    /**
     * @return string
     */
    public function getTillTimeMinutes()
    {
        return $this->getMinutesFromString($this->getTillTime());
    }

    /**
     * @param string $str
     * @return int
     */
    private function getHoursFromString($str)
    {
        return (int) substr($str, 0, 2);
    }

    /**
     * @param string $str
     * @return int
     */
    private function getMinutesFromString($str)
    {
        return (int) substr($str, 3, 2);
    }

    /**
     * @return string
     */
    public function getTillTime()
    {
        return $this->tillTime;
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }
}