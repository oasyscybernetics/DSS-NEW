<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Exception;

/**
 * Class ConfigurationException
 * @package Xibo\Exception
 */
class ConfigurationException extends XiboException
{
    public $httpStatusCode = 500;

    public function handledException()
    {
        return true;
    }
}