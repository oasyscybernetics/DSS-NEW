<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Event;

use Xibo\Entity\Media;
use Xibo\Widget\ModuleWidget;

class LibraryReplaceWidgetEvent extends Event
{
    public static $NAME = 'library.replace.widget.event';

    /** @var ModuleWidget */
    protected $module;

    /** @var \Xibo\Entity\Widget */
    protected $widget;

    /** @var Media */
    protected $newMedia;

    /** @var Media */
    protected $oldMedia;

    /**
     * WidgetEditEvent constructor.
     * @param ModuleWidget $module The Module for the item being uploaded (the replacement)
     * @param \Xibo\Entity\Widget $widget The Widget - it will already have the new media assigned.
     * @param Media $newMedia The replacement Media record
     * @param Media $oldMedia The old Media record
     */
    public function __construct($module, $widget, $newMedia, $oldMedia)
    {
        $this->module = $module;
        $this->widget = $widget;
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

    /**
     * @return \Xibo\Entity\Widget
     */
    public function getWidget()
    {
        return $this->widget;
    }
}