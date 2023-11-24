<?php

namespace App\Exceptions;

use Exception;

class AttribtuesAlreadyLoadedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Attributes have already been loaded.');
    }
}