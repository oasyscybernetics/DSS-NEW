<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Exception;

/**
 * Class XiboException
 * @package Xibo\Exception
 */
class XiboException extends \Exception
{
    public $httpStatusCode = 400;
    public $handledException = false;

    /**
     * @return bool
     */
    public function handledException()
    {
        return $this->handledException;
    }
}