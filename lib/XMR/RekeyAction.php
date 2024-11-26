<?php
/*
 * Oasys Digital Signage
 * 
 */

namespace Xibo\XMR;


class RekeyAction extends PlayerAction
{
    public function getMessage()
    {
        $this->action = 'rekeyAction';

        return $this->serializeToJson();
    }
}