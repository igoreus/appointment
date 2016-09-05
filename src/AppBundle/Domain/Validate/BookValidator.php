<?php

namespace AppBundle\Domain\Validate;

use AppBundle\Domain\Command\bookAppointment;
use AppBundle\Domain\Command\Command;
use AppBundle\Domain\Repository\AppointmentRepository;

class BookValidator implements Validator
{
    const NO_FOUND = 'Slot [%s] is not found';
    const ALREADY_BOOKED = 'Slot [%s] has been already booked';
    const WRONG_FROM_TIME = 'From time [%s] has wrong format';
    const WRONG_TILL_TIME = 'Till time [%s] has wrong format';
    const WRONG_INTERVAL = 'Interval [%s] is wrong';


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
        if (!($bookAppointment instanceof bookAppointment)) {
            throw new \LogicException('Command must be instance of AppBundle\Domain\Command\bookAppointment');
        }
        try {
            $entity = $this->repository->getById($bookAppointment->getId());
        } catch (\Exception $e) {
            $this->errors[] = sprintf(self::NO_FOUND, $bookAppointment->getId());
            return false;
        }

        if ($entity->getIsBooked() || $entity->getIsLocked()) {
            $this->errors[] = sprintf(self::ALREADY_BOOKED, $bookAppointment->getId());
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

}
