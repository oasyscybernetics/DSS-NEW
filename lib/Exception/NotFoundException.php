<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Exception;

/**
 * Class NotFoundException
 * @package Xibo\Exception
 */
class NotFoundException extends XiboException
{
    public $httpStatusCode = 404;
    public $handledException = true;
    public $entity = null;

    /**
     * NotFoundException constructor.
     * @param string $message
     * @param string $entity
     */
    public function __construct($message = null, $entity = null)
    {
        $this->entity = $entity;

        if ($message === null)
            $message = __('Not Found');

        parent::__construct($message);
    }

    /**
     * @return array
     */
    public function getErrorData()
    {
        return ['entity' => $this->entity];
    }
}