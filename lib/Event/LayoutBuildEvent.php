<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Event;

use Xibo\Entity\Layout;

/**
 * Class LayoutBuildEvent
 * @package Xibo\Event
 */
class LayoutBuildEvent extends Event
{
    const NAME = 'layout.build';

    /** @var  Layout */
    protected $layout;

    /** @var  \DOMDocument */
    protected $document;

    /**
     * LayoutBuildEvent constructor.
     * @param $layout
     * @param $document
     */
    public function __construct($layout, $document)
    {
        $this->layout = $layout;
        $this->document = $document;
    }

    /**
     * @return \DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }
}