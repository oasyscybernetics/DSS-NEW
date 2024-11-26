<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Exception;


class AccessDeniedException extends \RuntimeException
{
    /**
     * Public Constructor
     *
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = 'Access Denied', $code = 403, \Exception $previous = null)
    {
        $message = __($message);

        parent::__construct($message, $code, $previous);
    }
}