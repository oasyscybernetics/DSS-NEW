<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Event;

use Xibo\Entity\Media;
use Xibo\Widget\ModuleWidget;

class LibraryReplaceEvent extends Event
{
    public static $NAME = 'library.replace.event';

    /** @var ModuleWidget */
    protected $module;

    /** @var Media */
    protected $newMedia;

    /** @var Media */
    protected $oldMedia;

    /**
     * WidgetEditEvent constructor.
     * @param ModuleWidget $module
     * @param Media $newMedia
     * @param Media $oldMedia
     */
    public function __construct($module, $newMedia, $oldMedia)
    {
        $this->module = $module;
        $this->newMedia = $newMedia;
        $this->oldMedia = $oldMedia;
    }

    /**
     * @return ModuleWidget
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return Media
     */
    public function getOldMedia()
    {
        return $this->oldMedia;
    }

    /**
     * @return Media
     */
    public function getNewMedia()
    {
        return $this->newMedia;
    }
}