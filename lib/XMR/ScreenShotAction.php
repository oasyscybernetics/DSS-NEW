<?php
/*
 * Oasys Digital Signage
 * 
 */

namespace Xibo\XMR;


class ScreenShotAction extends PlayerAction
{
    public function getMessage()
    {
        $this->action = 'screenShot';

        return $this->serializeToJson();
    }
}