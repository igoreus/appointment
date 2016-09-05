<?php

namespace AppBundle\Entity;

use AppBundle\Domain\Model\Appointment as AppointmentModel;

class AppointmentFactory
{
    /**
     * @param AppointmentModel $appointmentModel
     * @return Appointment
     */
    public static function fromAppointmentToEntity(AppointmentModel $appointmentModel)
    {
        $appointment = new Appointment();

        $appointment->setDateTime($appointmentModel->getDateTime())
            ->setId($appointmentModel->getId())
            ->setIsBooked($appointmentModel->getIsBooked())
            ->setIsLocked($appointmentModel->getIsLocked())
            ->setClientName($appointmentModel->getClientName());

        return $appointment;
    }

    /**
     * @param Appointment $appointment
     * @return AppointmentModel
     */
    public static function fromEntityToAppointment(Appointment $appointment)
    {
        return new AppointmentModel(
            $appointment->getId(),
            $appointment->getDateTime(),
            $appointment->getIsBooked(),
            $appointment->getIsLocked(),
            $appointment->getClientName()
        );
    }
}