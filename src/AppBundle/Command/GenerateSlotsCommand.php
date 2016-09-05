<?php

namespace AppBundle\Command;

use AppBundle\Domain\Service\AppointmentService as DomainAppointmentService;
use AppBundle\Service\AppointmentService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use  AppBundle\Domain\Validate\GenerateSlotsValidator;

class GenerateSlotsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:generate-slots')
            ->setDescription('Generates time slots for given data and interval')
            ->addArgument('date', InputArgument::REQUIRED, 'Date in ISO-8601 format')
            ->addArgument('from-time', InputArgument::OPTIONAL, 'From Time, format: H:i. Default: 08:00', '08:00')
            ->addArgument('till-time', InputArgument::OPTIONAL, 'Till Time, format: H:i. Default: 18:00', '18:00')
            ->addArgument('interval', InputArgument::OPTIONAL, 'Interval in minutes eg. 15. Default: 60', '60');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em =  $this->getContainer()->get('doctrine')->getManager();
        $repository = new \AppBundle\Repository\AppointmentRepository($em, new ClassMetadata('AppBundle:Appointment'));

        $service = new AppointmentService(
            new DomainAppointmentService($repository)
        );
        try {
            $service->generateSlots(
                new GenerateSlotsValidator($repository),
                $input->getArgument('date'),
                $input->getArgument('from-time'),
                $input->getArgument('till-time'),
                $input->getArgument('interval')
            );
        } catch (\Exception $e) {
            $output->write(sprintf('Error(s) occur(s): %s', $e->getMessage()));
        }

    }
}
