<?php

namespace App\Services\Integrations\AlgarRms;

use RuntimeException;

final class AlgarRmsException extends RuntimeException
{
    public function __construct(string $operation, ?int $httpStatus = null)
    {
        $message = 'Algar RMS ' . $operation . ' failed';
        if ($httpStatus !== null) {
            $message .= ' (HTTP ' . $httpStatus . ')';
        }

        parent::__construct($message, $httpStatus ?? 0);
    }
}
