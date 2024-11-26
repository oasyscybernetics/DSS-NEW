<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Helper;


class ObjectVars
{
    /**
     * Get Object Properties
     * @param $object
     * @return array
     */
    public static function getObjectVars($object)
    {
        return get_object_vars($object);
    }
}