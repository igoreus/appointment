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
    public function isValid(Command $generateSlots)
    {
        $this->errors = [];
        if (!($generateSlots instanceof GenerateSlots)) {
            throw new \LogicException('Command must be instance of AppBundle\Domain\Command\GenerateSlots');
        }

        if ($this->repository->existingForDate($generateSlots->getDatetime())) {
            $this->errors[] = sprintf(self::ALREADY_EXISTS, $generateSlots->getDatetime()->format('Y-d-m'));
        }

        $this->validateTime('from_time', $generateSlots->getFromTime());
        $this->validateTime('till_time', $generateSlots->getTillTime());

        if ($generateSlots->getFromTime() >= $generateSlots->getTillTime()) {
            $this->errors[] = sprintf(
                self::TILL_TIME_LESS_THAN_FROM_TIME,
                $generateSlots->getFromTime(),
                $generateSlots->getTillTime()
            );
        }

        if ($generateSlots->getInterval() <= 0 || $generateSlots->getInterval() > 60) {
            $this->errors[] = sprintf(self::WRONG_INTERVAL, $generateSlots->getInterval());
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
