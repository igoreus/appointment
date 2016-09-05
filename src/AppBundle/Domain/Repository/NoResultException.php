<?php

namespace AppBundle\Domain\Repository;


class NoResultException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No result was found for query although at least one row was expected.');
    }
}