<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Factory;


use Xibo\Entity\WidgetAudio;
use Xibo\Service\LogServiceInterface;
use Xibo\Service\SanitizerServiceInterface;
use Xibo\Storage\StorageServiceInterface;

/**
 * Class WidgetAudioFactory
 * @package Xibo\Factory
 */
class WidgetAudioFactory extends BaseFactory
{
    /**
     * Construct a factory
     * @param StorageServiceInterface $store
     * @param LogServiceInterface $log
     * @param SanitizerServiceInterface $sanitizerService
     */
    public function __construct($store, $log, $sanitizerService)
    {
        $this->setCommonDependencies($store, $log, $sanitizerService);
    }

    /**
     * Create Empty
     * @return WidgetAudio
     */
    public function createEmpty()
    {
        return new WidgetAudio($this->getStore(), $this->getLog());
    }

    /**
     * Media Linked to Widgets by WidgetId
     * @param int $widgetId
     * @return array[int]
     */
    public function getByWidgetId($widgetId)
    {
        return $this->query(null, array('widgetId' => $widgetId));
    }

    /**
     * Query Media Linked to Widgets
     * @param array $sortOrder
     * @param array $filterBy
     * @return array[int]
     */
    public function query($sortOrder = null, $filterBy = [])
    {
        $entries = [];
        $sql = 'SELECT `mediaId`, `widgetId`, `volume`, `loop` FROM `lkwidgetaudio` WHERE widgetId = :widgetId AND mediaId <> 0 ';

        foreach ($this->getStore()->select($sql, ['widgetId' => $this->getSanitizer()->getInt('widgetId', $filterBy)]) as $row) {
            $entries[] = $this->createEmpty()->hydrate($row, ['intProperties' => ['duration']]);
        }

        return $entries;
    }
}