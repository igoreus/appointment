<?php
namespace AppBundle\Domain\Command;

use AppBundle\Domain\Validate\Validator;

abstract class AbstractCommand implements Command
{
    /** @var  Validator */
    protected $validator;

    /**
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param Validator $validator
     */
    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->validator->isValid($this);
    }
}