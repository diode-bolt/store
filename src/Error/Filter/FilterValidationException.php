<?php

namespace App\Error\Filter;

class FilterValidationException extends \RuntimeException
{
    public function __construct(string $message, public string $path, public string $value)
    {
        parent::__construct($message);
    }
}