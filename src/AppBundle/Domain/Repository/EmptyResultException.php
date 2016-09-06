<?php

namespace AppBundle\Domain\Repository;


class EmptyResultException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No result was found for query although at least one row was expected.');
    }
}
