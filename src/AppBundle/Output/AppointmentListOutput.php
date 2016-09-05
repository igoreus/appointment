<?php

namespace AppBundle\Output;

use AppBundle\Domain\Model\Appointment;
use AppBundle\Domain\Model\AppointmentSet;

class AppointmentListOutput
{
    const OK = 'ok';
    const GROUP = 'appointments';

    /**
     * @param AppointmentSet $appointmentSet
     * @return array
     */
    public static function toArray(AppointmentSet $appointmentSet)
    {
        $arr = [self::OK => true, self::GROUP => []];

        /** @var Appointment $appointment */
        foreach ($appointmentSet as $appointment) {
            $arr[self::GROUP][] = [
                'id' => $appointment->getId(),
                'date_time' =>$appointment->getDateTime()->format('Y-m-d H:i'),
                'is_booked' => $appointment->getIsBooked(),
                'client_name' => $appointment->getClientName(),
            ];
        }

        return $arr;
    }
}