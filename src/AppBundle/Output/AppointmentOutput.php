<?php

namespace AppBundle\Output;

use AppBundle\Domain\Model\Appointment;

class AppointmentOutput
{
    const OK = 'ok';
    const GROUP = 'appointment';

    /**
     * @param Appointment $appointment
     * @return array
     */
    public static function toArray(Appointment $appointment)
    {
        return [
            self::OK => true,
            self::GROUP => [
                'id' => $appointment->getId(),
                'date_time' =>$appointment->getDateTime()->format('Y-m-d H:i'),
                'is_booked' => $appointment->getIsBooked(),
                'client_name' => $appointment->getClientName(),
            ]
        ];
    }
}