<?php

namespace App\Exceptions;

use App\Exceptions\HttpException;

class NotFoundException extends HttpException
{
    public function __construct(
        string $message,
        string $errorCode = 'RESOURCE_NOT_FOUND',
        string|array|null $details = null
    ) {
        parent::__construct($message, 404, $errorCode, $details);
    }
}

