<?php
/*
 * Oasys Digital Signage
 * 
 */


namespace Xibo\XMR;

/**
 * Class ClearStatsAndLogsAction
 * @package Xibo\XMR
 */
class ClearStatsAndLogsAction extends PlayerAction
{
    public function getMessage()
    {
        $this->action = 'clearStatsAndLogs';

        return $this->serializeToJson();
    }
}