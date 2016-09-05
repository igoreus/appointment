<?php

namespace AppBundle\Domain\Validate;

use AppBundle\Domain\Command\Command;
use AppBundle\Domain\Command\GenerateSlots;
use AppBundle\Domain\Repository\AppointmentRepository;

class GenerateSlotsValidator implements Validator
{
    const ALREADY_EXISTS = 'Data has been already generated for this date [%s]';
    const TILL_TIME_LESS_THAN_FROM_TIME = 'from_time [%s] should be less than till_time [%s]';
    const WRONG_TIME = '%s [%s] has wrong format';
    const WRONG_INTERVAL = 'Interval [%s] is wrong. Interval must be between 1 and 60 minutes';

    /** @var AppointmentRepository */
    private $repository;
    /** @var array */
    private $errors = [];
    /**
     * @param AppointmentRepository $repository
     */
    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function isValid(Command $bookAppointment)
    {
        $this->errors = [];
        if (!($bookAppointment instanceof GenerateSlots)) {
            throw new \LogicException('Command must be instance of AppBundle\Domain\Command\GenerateSlots\GenerateSlots');
        }

        if ($this->repository->existingForDate($bookAppointment->getDatetime())) {
            $this->errors[] = sprintf(self::ALREADY_EXISTS, $bookAppointment->getDatetime()->format('Y-d-m'));
        }

        $this->validateTime('from_time', $bookAppointment->getFromTime());
        $this->validateTime('till_time', $bookAppointment->getTillTime());

        if ($bookAppointment->getFromTime() >= $bookAppointment->getTillTime()) {
            $this->errors[] = sprintf(
                self::TILL_TIME_LESS_THAN_FROM_TIME,
                $bookAppointment->getFromTime(),
                $bookAppointment->getTillTime()
            );
        }

        if ($bookAppointment->getInterval() <= 0 || $bookAppointment->getInterval() > 60) {
            $this->errors[] = sprintf(self::WRONG_INTERVAL, $bookAppointment->getInterval());
        }


        return empty($this->errors);
    }

    /**
     * @param string $name
     * @param string $time
     */
    private function validateTime($name, $time)
    {
        if (!preg_match('/\d\d\:\d\d/', $time)) {
            $this->errors[] = sprintf(self::WRONG_TIME, $name, $time);
            return;
        }

        list($hours, $minutes) = explode(':', $time);

        if ($hours < 0 || $hours > 23 || $minutes < 0 || $minutes > 59) {
            $this->errors[] = sprintf(self::WRONG_TIME, $name, $time);
        }

    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

}
