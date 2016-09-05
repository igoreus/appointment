<?php

namespace AppBundle\Domain\Command;
use AppBundle\Domain\Validate\Validator;

/**
 * Interface Command
 * Describes a user intention to modify the state of the system
 */
interface Command
{
    /**
     * @return Validator
     */
    public function getValidator();

    /**
     * @param Validator $validator
     */
    public function setValidator(Validator $validator);

    /**
     * @return bool
     */
    public function isValid();
}
