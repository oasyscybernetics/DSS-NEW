<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

namespace Xibo\Event;


abstract class Event extends \Symfony\Component\EventDispatcher\Event
{
    private static $NAME = 'generic.event';

    public function getName()
    {
        return $this::$NAME;
    }
}