<?php
/*
 * Oasys Digital Signage
 * 
 */


namespace Xibo\XMR;

/**
 * Class CollectNowAction
 * @package Xibo\XMR
 */
class CollectNowAction extends PlayerAction
{
    /**
     * @inheritdoc
     */
    public function getMessage()
    {
        $this->setQos(1);
        $this->action = 'collectNow';

        return $this->serializeToJson();
    }
}