<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Exception;

/**
 * Class DuplicateEntityException
 * @package Xibo\Exception
 */
class DuplicateEntityException extends XiboException
{
    public $httpStatusCode = 409;

    /**
     * @return bool
     */
    public function handledException()
    {
        return true;
    }
}