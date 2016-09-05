<?php

namespace AppBundle\Repository;

use AppBundle\Domain\Model\Appointment as AppointmentModel;
use AppBundle\Domain\Model\AppointmentSet;

use AppBundle\Entity\AppointmentFactory;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;


use AppBundle\Domain\Repository\AppointmentRepository as AppointmentRepositoryInterface;
use AppBundle\Domain\Repository\NoResultException;


class AppointmentRepository extends EntityRepository implements AppointmentRepositoryInterface
{

    /**
     * @param AppointmentSet $appointmentSet
     * @return bool
     */
    public function persistAppointmentSet(AppointmentSet $appointmentSet)
    {
        /** @var AppointmentModel $appointment */
        foreach ($appointmentSet as $appointment) {
            $this->getEntityManager()->persist(AppointmentFactory::fromAppointmentToEntity($appointment));
        }

        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();

        return true;
    }

    /**
     * @param AppointmentModel $appointment
     * @return bool
     */
    public function mergeAppointment(AppointmentModel $appointment)
    {
        $this->getEntityManager()->merge(AppointmentFactory::fromAppointmentToEntity($appointment));
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function existingForDate(\DateTime $date)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $query = $qb->select('a')
            ->from('AppBundle\Entity\Appointment', 'a')
            ->where('a.dateTime >= :dateFrom')
            ->andWhere('a.dateTime <= :dateTill')
            ->setParameter('dateFrom', $date->format('Y-m-d 00:00:00'))
            ->setParameter('dateTill', $date->format('Y-m-d 23:59:59'))
            ->getQuery();

        return (bool) $query->getResult();
    }

    /**
     * @inheritdoc
     */
    public function getList(\DateTime $date)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $query = $qb->select('a')
            ->from('AppBundle\Entity\Appointment', 'a')
            ->where('a.dateTime >= :dateFrom')
            ->andWhere('a.dateTime <= :dateTill')
            ->setParameter('dateFrom', $date->format('Y-m-d 00:00:00'))
            ->setParameter('dateTill', $date->format('Y-m-d 23:59:59'))
            ->getQuery();

        $appointmentSet = new AppointmentSet();
        foreach ($query->getResult() as $entity) {
            $appointmentSet->addAppointment(AppointmentFactory::fromEntityToAppointment($entity));
        }

        return $appointmentSet;
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        $em = $this->getEntityManager();

        $entity = $em->find('AppBundle\Entity\Appointment', $id);

        if (empty($entity)) {
            throw new NoResultException();
        }

        return AppointmentFactory::fromEntityToAppointment($entity);
    }

    /**
     * @inheritdoc
     */
    public function getAndAcquireLock($id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $em->getConnection()->beginTransaction();
        $query = $qb->select('a')
            ->from('AppBundle\Entity\Appointment', 'a')
            ->where('a.id = :id')
            ->andWhere('a.isLocked = 0')
            ->andWhere('a.isBooked = 0')
            ->setParameter('id', $id)
            ->getQuery();

        $query->setLockMode(LockMode::PESSIMISTIC_WRITE);

        $entity = $query->getOneOrNullResult();

        if (empty($entity)) {
            $em->getConnection()->rollBack();
            throw new NoResultException();
        }
        $entity->setIslocked(1);
        $this->getEntityManager()->merge($entity);
        $this->getEntityManager()->flush();

        $em->getConnection()->commit();

        return AppointmentFactory::fromEntityToAppointment($entity);
    }
}