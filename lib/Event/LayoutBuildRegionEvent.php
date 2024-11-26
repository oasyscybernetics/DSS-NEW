<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
namespace Xibo\Event;

/**
 * Class LayoutBuildRegionEvent
 * @package Xibo\Event
 */
class LayoutBuildRegionEvent extends Event
{
    const NAME = 'layout.build.region';

    /** @var  int */
    protected $regionId;

    /** @var  \DOMElement */
    protected $regionNode;

    /**
     * LayoutBuildEvent constructor.
     * @param int $regionId
     * @param \DOMElement $regionNode
     */
    public function __construct($regionId, $regionNode)
    {
        $this->regionId = $regionId;
        $this->regionNode = $regionNode;
    }

    /**
     * @return \DOMElement
     */
    public function getRegionNode()
    {
        return $this->regionNode;
    }
}