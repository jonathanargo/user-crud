<?php

namespace App\Exceptions;

use Exception;

class AttributeDoesNotExistException extends Exception
{
    public function __construct(string $attribute)
    {
        parent::__construct("Attribute {$attribute} does not exist.");
    }
}