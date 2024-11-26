<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Event;

use Xibo\Widget\ModuleWidget;

class WidgetEditEvent extends Event
{
    public static $NAME = 'widget.edit';

    /** @var ModuleWidget */
    protected $module;

    /**
     * WidgetEditEvent constructor.
     * @param ModuleWidget $module
     */
    public function __construct($module)
    {
        $this->module = $module;
    }

    /**
     * @return ModuleWidget
     */
    public function getModule()
    {
        return $this->module;
    }
}