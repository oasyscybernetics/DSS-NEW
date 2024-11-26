<?php
/*
 * Oasys Digital Signage
 * 
 */
namespace Xibo\XMR;

/**
 * Class LicenceCheckAction
 * @package Xibo\XMR
 */
class LicenceCheckAction extends PlayerAction
{
    /**
     * @return mixed|string
     */
    public function getMessage()
    {
        $this->action = 'licenceCheck';

        return $this->serializeToJson();
    }
}