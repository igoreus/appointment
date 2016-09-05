<?php
namespace AppBundle\Domain\Command;

use AppBundle\Domain\Validate\BookValidator;

class bookAppointment extends AbstractCommand
{
    /** @var int */
    private $id;
    /** @var string */
    private $clientName;
    /**
     * @param BookValidator $validator
     * @param int $id
     * @param string $clientName
     */
    public function __construct(BookValidator $validator, $id, $clientName)
    {
        $this->id = (int) $id;
        $this->clientName = $clientName;
        $this->setValidator($validator);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }
}