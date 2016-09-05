<?php

namespace AppBundle\Output;

use AppBundle\Domain\Model\Appointment;
use AppBundle\Domain\Model\AppointmentSet;

class AppointmentListOutput
{
    const OK = 'ok';
    const GROUP = 'appointments';

    public static function toArray(AppointmentSet $appointmentSet)
    {
        $arr = [self::OK => true, self::GROUP => []];

        $appointmentOutput = new AppointmentOutput();

        /** @var Appointment $appointment */
        foreach ($appointmentSet as $appointment) {
            $arr[self::GROUP][] = [
                'id' => $appointment->getId(),
                'date_time' =>$appointment->getDateTime()->format('Y-m-d h:i'),
                'is_booked' => $appointment->getIsBooked(),
                'client_name' => $appointment->getClientName(),
            ];
        }

        return $arr;
    }
}