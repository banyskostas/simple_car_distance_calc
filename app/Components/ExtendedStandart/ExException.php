<?php
namespace App\Components\ExtendedClasses;
use Exception;

/**
 * Class ExException
 * @package App\Components\Traits
 */
class ExException extends Exception
{
    const UNSUCCESSFULL_REQUEST_MSG = "For more information please contact us.";

    /**
     * @param Exception $e
     * @return string
     */
    public static function generateMsg(Exception $e)
    {
        return "{$e->getMessage()} on file: {$e->getFile()} on line: {$e->getLine()}";
    }
}