<?php
/*
 * Oasys Digital Signage
 * 
 */


namespace Xibo\XMR;


class CommandAction extends PlayerAction
{
    protected $commandCode;

    /**
     * Set the command code
     * @param string $code
     * @return $this
     */
    public function setCommandCode($code)
    {
        $this->commandCode = $code;
        return $this;
    }

    public function getMessage()
    {
        $this->action = 'commandAction';

        if ($this->commandCode == '')
            throw new PlayerActionException('Missing Command Code');

        return $this->serializeToJson(['commandCode']);
    }
}