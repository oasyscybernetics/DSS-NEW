<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

namespace Xibo\Helper;

/**
 * Class Profiler
 * @package Xibo\Helper
 */
class Profiler
{
    private static $profiles = [];

    public static function start($key, $logger = null)
    {
        $start = microtime(true);
        self::$profiles[$key] = $start;

        if ($logger !== null) {
            $logger->debug('PROFILE: ' . $key . ' - start: ' . $start);
        }
    }

    public static function end($key, $logger = null)
    {
        $start = self::$profiles[$key] ?? 0;
        $end = microtime(true);
        unset(self::$profiles[$key]);

        if ($logger !== null) {
            $logger->debug('PROFILE: ' . $key . ' - end: ' . $end
                . ', duration: ' . ($end - $start));
        }
    }
}
