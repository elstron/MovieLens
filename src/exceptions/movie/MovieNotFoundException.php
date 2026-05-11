<?php
namespace App\Exceptions;

use RuntimeException; // o use Exception;

class MovieNotFoundException extends RuntimeException
{
    public function __construct(string $message = "La película no fue encontrada.", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}