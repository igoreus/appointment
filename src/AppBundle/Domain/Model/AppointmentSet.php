<?php

namespace AppBundle\Domain\Model;

class AppointmentSet implements \Iterator, \Countable
{
    /** @var [] */
    private $appointmentList = [];

    /**
     * @param Appointment $appointment
     */
    public function addAppointment(Appointment $appointment)
    {
        $this->appointmentList[] = $appointment;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return current($this->appointmentList);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        next($this->appointmentList);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->appointmentList);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return key($this->appointmentList) !== null;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        reset($this->appointmentList);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->appointmentList);
    }

    /**
     * @return Appointment[]
     */
    public function getAppointmentList()
    {
        return $this->appointmentList;
    }


}
