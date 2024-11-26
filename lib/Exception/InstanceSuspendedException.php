<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Exception;


class InstanceSuspendedException extends \Exception
{
    public $httpStatusCode = 403;

    /**
     * Public Constructor
     *
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = 'Instance Suspended', $code = 403, \Exception $previous = null)
    {
        $message = __($message);

        parent::__construct($message, $code, $previous);
    }
}