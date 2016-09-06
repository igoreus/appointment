<?php

namespace AppBundle\Domain\Tests\Service;


use AppBundle\Domain\Command\BookAppointment;
use AppBundle\Domain\Command\GenerateSlots;
use AppBundle\Domain\Model\Appointment;
use AppBundle\Domain\Model\AppointmentSet;
use AppBundle\Domain\Repository\AppointmentRepository;
use AppBundle\Domain\Repository\EmptyResultException;
use AppBundle\Domain\Service\AppointmentService;
use AppBundle\Domain\Validate\BookValidator;
use AppBundle\Domain\Validate\GenerateSlotsValidator;


class AppointmentServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getByIdExists()
    {
        $id = 1;
        $expectedAppointment = new Appointment($id, new \DateTime(), 0, 0, '');

        /** @var AppointmentRepository $repository */
        $repository = $this->getMock(AppointmentRepository::class);
        $repository->expects($this->once())
            ->method('getById')
            ->willReturn(clone $expectedAppointment)
            ->with($id);

        $service = new AppointmentService($repository);
        $appointment = $service->getById($id);

        $this->assertEquals($expectedAppointment, $appointment);
    }

    /**
     * @test
     * @expectedException AppBundle\Domain\Repository\EmptyResultException
     */
    public function getByIdDoesNotExist()
    {
        $id = 1;

        $repository = $this->getMock(AppointmentRepository::class);
        $repository->expects($this->once())
            ->method('getById')
            ->willThrowException(new EmptyResultException())
            ->with($id);

        $service = new AppointmentService($repository);
        $service->getById($id);
    }

    /**
     * @test
     */
    public function getListNotEmpty()
    {
        $dateTime = new \DateTime();
        $expectedAppointmentSet = new AppointmentSet();
        $expectedAppointmentSet->addAppointment(new Appointment(1, $dateTime, 0, 0, ''));
        $expectedAppointmentSet->addAppointment(new Appointment(2, $dateTime, 0, 0, ''));

        /** @var AppointmentRepository $repository */
        $repository = $this->getMock(AppointmentRepository::class);
        $repository->expects($this->once())
            ->method('getList')
            ->willReturn(clone $expectedAppointmentSet)
            ->with($dateTime);

        $service = new AppointmentService($repository);
        $appointmentSet = $service->getList($dateTime);

        $this->assertEquals($expectedAppointmentSet->getAppointmentList(), $appointmentSet->getAppointmentList());
    }

    /**
     * @test
     */
    public function getLisEmpty()
    {
        $dateTime = new \DateTime();
        $expectedAppointmentSet = new AppointmentSet();

        /** @var AppointmentRepository $repository */
        $repository = $this->getMock(AppointmentRepository::class);
        $repository->expects($this->once())
            ->method('getList')
            ->willReturn(clone $expectedAppointmentSet)
            ->with($dateTime);

        $service = new AppointmentService($repository);
        $appointmentSet = $service->getList($dateTime);

        $this->assertEquals($expectedAppointmentSet->getAppointmentList(), $appointmentSet->getAppointmentList());
    }

    /**
     * @test
     */
    public function generateSlotsSuccess()
    {
        $date = '2016-01-01';
        $fromTime = '08:00';
        $tillTime = '12:30';
        $interval = '60';
        $expectedAppointmentTime = [
            new \DateTime($date . '08:00'),
            new \DateTime($date . '09:00'),
            new \DateTime($date . '10:00'),
            new \DateTime($date . '11:00'),
            new \DateTime($date . '12:00'),
        ];

        $appointmentSet = new AppointmentSet();

        foreach($expectedAppointmentTime as $time) {
            $appointmentSet->addAppointment(Appointment::createNew($time));
        }

        /** @var AppointmentRepository $repository */
        $repository = $this->getMock(AppointmentRepository::class);
        $repository->expects($this->once())
            ->method('existingForDate')
            ->willReturn(false)
            ->with(new \DateTime($date));

        $repository->expects($this->once())
            ->method('persistAppointmentSet')
            ->willReturn(true)
            ->with($appointmentSet);

        $command = new GenerateSlots(new GenerateSlotsValidator($repository), $date, $fromTime, $tillTime, $interval);
        $service = new AppointmentService($repository);

        $this->assertTrue($service->generateSlots($command));
    }

    /**
     * @test
     */
    public function bookAppointmentSuccess()
    {
        $id = 1;
        $dateTime = new \DateTime('2016-01-01 08:00');
        $name = 'John Smith';
        $notBookedAppointment = new Appointment($id, $dateTime, 0, 0, '');
        $lockedAppointment = new Appointment($id, $dateTime, 0, 1, '');
        $bookedAppointment = new Appointment($id, $dateTime, 1, 0, $name);

        /** @var AppointmentRepository $repository */
        $repository = $this->getMock(AppointmentRepository::class);
        $repository->expects($this->once())
            ->method('getById')
            ->willReturn(clone $notBookedAppointment)
            ->with($id);

        $repository->expects($this->once())
            ->method('getAndAcquireLock')
            ->willReturn($lockedAppointment)
            ->with($id);

        $repository->expects($this->once())
            ->method('mergeAppointment')
            ->willReturn(true)
            ->with($bookedAppointment);

        $command = new BookAppointment(new BookValidator($repository), $id, $name);
        $service = new AppointmentService($repository);

        $this->assertTrue($service->bookAppointment($command));
    }
}