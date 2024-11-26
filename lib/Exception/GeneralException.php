<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

namespace Xibo\Exception;

/**
 * Class GeneralException
 * @package Xibo\Exception
 */
class GeneralException extends XiboException
{
    public $httpStatusCode = 400;
    public $handledException = true;
    public $entity = null;

    /**
     * GeneralException constructor.
     * @param string $message
     * @param string $entity
     */
    public function __construct($message = null, $entity = null)
    {
        $this->entity = $entity;

        if ($message === null)
            $message = __('Error');

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