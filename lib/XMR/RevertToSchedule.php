<?php
/*
 * Oasys Digital Signage
 * 
 */


namespace Xibo\XMR;


class RevertToSchedule extends PlayerAction
{
    public function getMessage()
    {
        $this->action = 'revertToSchedule';

        return $this->serializeToJson();
    }
}