<?php

namespace AppBundle\Domain\Service;

use AppBundle\Domain\Command\bookAppointment;
use AppBundle\Domain\Model\Appointment;
use AppBundle\Domain\Command\GenerateSlots;
use AppBundle\Domain\Model\AppointmentSet;
use AppBundle\Domain\Repository\AppointmentRepository;

class AppointmentService
{
    /** @var AppointmentRepository */
    private $repository;

    /**
     * @param AppointmentRepository $repository
     */
    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \DateTime $dateTime
     * @return AppointmentSet
     */
    public function getList(\DateTime $dateTime)
    {
        return $this->repository->getList($dateTime);
    }

    /**
     * @param int $id
     * @return Appointment
     */
    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    /**
     * @param GenerateSlots $generateSlots
     * @return bool
     */
    public function generateSlots(GenerateSlots $generateSlots)
    {
        if (!$generateSlots->isValid()) {
            return false;
        };

        $appointmentSet = new AppointmentSet();

        $fromTime = $generateSlots->getDatetime()->setTime(
            $generateSlots->getFromTimeHours(),
            $generateSlots->getFromTimeMinutes()
        );

        $tillTime = $generateSlots->getDatetime()->setTime(
            $generateSlots->getTillTimeHours(),
            $generateSlots->getTillTimeMinutes()
        );

        do {
            $appointmentSet->addAppointment(
                Appointment::createNew($fromTime)
            );
            $fromTime->modify(sprintf('+ %d minutes', $generateSlots->getInterval()));

        } while ($fromTime <= $tillTime);

        return $this->repository->persistAppointmentSet($appointmentSet);
    }

    /**
     * @param bookAppointment $bookAppointment
     * @return bool
     */
    public function bookAppointment(bookAppointment $bookAppointment)
    {
        if (!$bookAppointment->isValid()) {
            return false;
        };

        $appointment = $this->repository->getAndAcquireLock($bookAppointment->getId());

        $appointment->setIsBooked(true);
        $appointment->setIsLocked(false);
        $appointment->setClientName($bookAppointment->getClientName());

        return $this->repository->mergeAppointment($appointment);
    }
}
