<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Helper;


use Slim\Slim;

/**
 * Class LogProcessor
 * @package Xibo\Helper
 */
class LogProcessor
{
    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $app = Slim::getInstance();

        if ($app === null)
            return $record;

        $record['extra']['method'] = $app->request()->getMethod();
        $record['extra']['route'] = $app->request()->getResourceUri();

        if ($app->user != null)
            $record['extra']['userId'] = $app->user->userId;

        return $record;
    }
}