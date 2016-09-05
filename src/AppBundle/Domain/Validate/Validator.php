<?php

namespace AppBundle\Domain\Validate;

use AppBundle\Domain\Command\Command;

interface Validator
{
    /**
     * @param Command $command
     * @return boolean
     */
    public function isValid(Command $command);

    /**
     * @return array
     */
    public function getErrors();
}