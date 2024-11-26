<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Event;

use Xibo\Widget\ModuleWidget;

class WidgetAddEvent extends Event
{
    public static $NAME = 'widget.add';

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