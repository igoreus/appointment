<?php

namespace AppBundle\Service;

use AppBundle\Domain\Command\BookAppointment;
use AppBundle\Domain\Command\GenerateSlots;
use AppBundle\Domain\Service\AppointmentService as DomainAppointmentService;
use AppBundle\Domain\Validate\BookValidator;
use AppBundle\Domain\Validate\GenerateSlotsValidator;

class AppointmentService
{
    /** @var DomainAppointmentService */
    private $service;

    /**
     * @param DomainAppointmentService $service
     */
    public function __construct(DomainAppointmentService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $dateTime
     * @return \AppBundle\Domain\Model\AppointmentSet
     */
    public function getAppointmentList($dateTime)
    {
        return $this->service->getList(new \DateTime($dateTime));
    }

    /**
     * @param $id
     * @return \AppBundle\Domain\Model\Appointment
     */
    public function getAppointment($id)
    {
        return $this->service->getById($id);
    }

    /**
     * @param GenerateSlotsValidator $validator
     * @param string $dateTime
     * @param string $fromTime
     * @param string $tillTime
     * @param string $interval
     * @return bool
     * @throws \Exception
     */
    public function generateSlots(GenerateSlotsValidator $validator, $dateTime, $fromTime, $tillTime, $interval)
    {
        $command = new GenerateSlots($validator, $dateTime, $fromTime, $tillTime, $interval);
        $result = $this->service->generateSlots($command);

        if ($result == false) {
            throw new \Exception(join(' .', $command->getValidator()->getErrors()));
        }

        return $result;
    }

    /**
     * @param BookValidator $validator
     * @param int $id
     * @param string $clientName
     * @return bool
     * @throws \Exception
     */
    public function bookAppointment(BookValidator $validator, $id, $clientName)
    {
        $command = new BookAppointment($validator, $id, $clientName);
        $result = $this->service->bookAppointment($command);

        if ($result == false) {
            throw new \Exception(join(' .', $command->getValidator()->getErrors()));
        }

        return $result;
    }
}