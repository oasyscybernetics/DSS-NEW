<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Event;


use Xibo\Entity\DisplayProfile;

/**
 * Class DisplayProfileLoadedEvent
 * @package Xibo\Event
 */
class DisplayProfileLoadedEvent extends Event
{
    const NAME = 'displayProfile.load';

    /** @var  DisplayProfile */
    protected $displayProfile;

    /**
     * DisplayProfileLoadedEvent constructor.
     * @param $displayProfile
     */
    public function __construct($displayProfile)
    {
        $this->displayProfile = $displayProfile;
    }

    /**
     * @return DisplayProfile
     */
    public function getDisplayProfile()
    {
        return $this->displayProfile;
    }
}