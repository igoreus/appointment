<?php

namespace AppBundle\Domain\Tests\Command;

use AppBundle\Domain\Command\GenerateSlots;
use AppBundle\Domain\Repository\AppointmentRepository;
use AppBundle\Domain\Validate\GenerateSlotsValidator;

class GenerateSlotsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function createValidCommand()
    {
        $date = '2016-01-01';
        $fromTime = '08:00';
        $tillTime = '18:00';
        $interval = '60';

        /** @var AppointmentRepository $repository */
        $repository = $this->getMock(AppointmentRepository::class);
        $repository->expects($this->once())
            ->method('existingForDate')
            ->willReturn(false)
            ->with(new \DateTime($date));

        $validator = new GenerateSlotsValidator($repository);
        $command = new GenerateSlots($validator, $date, $fromTime, $tillTime, $interval);

        $this->assertTrue($command->isValid());
        $this->assertEquals('08', $command->getFromTimeHours());
        $this->assertEquals('00', $command->getFromTimeMinutes());
        $this->assertEquals('18', $command->getTillTimeHours());
        $this->assertEquals('00', $command->getTillTimeMinutes());


    }

}