<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Validate\BookValidator;
use AppBundle\Output\AppointmentErrorOutput;
use AppBundle\Output\AppointmentListOutput;
use AppBundle\Output\AppointmentOutput;
use AppBundle\Service\AppointmentService;
use AppBundle\Domain\Service\AppointmentService as DomainAppointmentService;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends FOSRestController
{
    /**
     * @Rest\GET("/api/v1/appointment/list/{date}")
     */
    public function GetListAction(Request $request)
    {
        $service = new AppointmentService(new DomainAppointmentService($this->getAppointmentRepository()));
        try {
            $appointmentSet = $service->getAppointmentList($request->get('date'));
            $outputArray = (new AppointmentListOutput())->toArray($appointmentSet);
            $responseCode =  Response::HTTP_OK;

        } catch (\Exception $e) {
            $outputArray = (new AppointmentErrorOutput())->toArray($e);
            $responseCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
            /** @var Logger $logger */
            $logger = $this->get('logger');
            $logger->error(sprintf('API Error occurs [%s]: %s', $request->getUri(), $e->getMessage()));
        }

        return $this->view($outputArray, $responseCode);
    }

    /**
     * @Rest\GET("/api/v1/appointment/{id}")
     */
    public function GetAction(Request $request)
    {
        $service = new AppointmentService(new DomainAppointmentService($this->getAppointmentRepository()));
        try {
            $appointment = $service->getAppointment($request->get('id'));
            $outputArray = (new AppointmentOutput())->toArray($appointment);
            $responseCode =  Response::HTTP_OK;

        } catch (\Exception $e) {
            $outputArray = (new AppointmentErrorOutput())->toArray($e);
            $responseCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
            /** @var Logger $logger */
            $logger = $this->get('logger');
            $logger->error(sprintf('API Error occurs [%s]: %s', $request->getUri(), $e->getMessage()));
        }

        return $this->view($outputArray, $responseCode);
    }

    /**
     * @Rest\PUT("/api/v1/appointment/book")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="id", nullable=false, strict=true, description="id")
     * @RequestParam(name="client_name", nullable=false, strict=true, description="Client Name")
     */
    public function PutBookAction(ParamFetcher $paramFetcher)
    {
        $service = new AppointmentService(new DomainAppointmentService($this->getAppointmentRepository()));
        try {
            $service->bookAppointment(
                new BookValidator($this->getAppointmentRepository()),
                $paramFetcher->get('id'),
                $paramFetcher->get('client_name')
            );
            $outputArray = (new AppointmentOutput())->toArray($service->getAppointment($paramFetcher->get('id')));
            $responseCode =  Response::HTTP_OK;

        } catch (\Exception $e) {
            $outputArray = (new AppointmentErrorOutput())->toArray($e);
            $responseCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
            /** @var Logger $logger */
            $logger = $this->get('logger');
            $logger->error(sprintf('API Error occurs [/api/v1/appointment/book]: %s', $e->getMessage()));
        }
        return $this->view($outputArray, $responseCode);
    }

    /**
     * @return \AppBundle\Repository\AppointmentRepository
     */
    private function getAppointmentRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = new \AppBundle\Repository\AppointmentRepository($em, new ClassMetadata('AppBundle:Appointment'));

        return $repository;
    }
}
