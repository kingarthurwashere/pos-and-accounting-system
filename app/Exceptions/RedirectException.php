<?php

namespace App\Exceptions;

use Exception;

class RedirectException extends Exception
{
    protected $statusCode;

    public function __construct($message = "", $statusCode = 403)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
