<?php
namespace App\Components\Exceptions;

use Exception;

/**
 * Class MyException
 * @package App\Components\Exceptions
 */
class ValidationException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString() {
        return "{$this->message}";
    }
}