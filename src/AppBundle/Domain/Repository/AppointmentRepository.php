<?php

namespace AppBundle\Domain\Repository;

use AppBundle\Domain\Model\Appointment;
use AppBundle\Domain\Model\AppointmentSet;

interface AppointmentRepository
{
    /**
     * @param \DateTime $date
     * @return boolean
     */
    public function existingForDate(\DateTime $date);

    /**
     * @param AppointmentSet $appointmentSet
     * @return bool
     */
    public function persistAppointmentSet(AppointmentSet $appointmentSet);

    /**
     * @param Appointment $appointment
     * @return bool
     */
    public function mergeAppointment(Appointment $appointment);

    /**
     * @param \DateTime $datetime
     * @return AppointmentSet
     */
    public function getList(\DateTime $datetime);

    /**
     * @param int $id
     * @return Appointment
     *
     * @Exception EmptyResultException
     */
    public function getById($id);

    /**param int $id
     * @return Appointment
     */
    public function getAndAcquireLock($id);

}