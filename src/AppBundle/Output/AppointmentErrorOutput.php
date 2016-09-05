<?php

namespace AppBundle\Output;

class AppointmentErrorOutput
{
    const OK = 'ok';
    const GROUP = 'error';

    /**
     * @param \Exception $exception
     * @return array
     */
    public static function toArray(\Exception $exception)
    {
        return [
            self::OK => false,
            self::GROUP => [
                'message' => $exception->getMessage(),
            ]
        ];
    }
}