<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Factory;


use Xibo\Service\LogServiceInterface;
use Xibo\Service\SanitizerServiceInterface;
use Xibo\Storage\StorageServiceInterface;

class WidgetMediaFactory extends BaseFactory
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
     * Media Linked to Widgets by WidgetId
     * @param int $widgetId
     * @return array[int]
     */
    public function getByWidgetId($widgetId)
    {
        return $this->query(null, array('widgetId' => $widgetId));
    }

    /**
     * Media Linked to Widgets by WidgetId
     * @param int $widgetId
     * @return array[int]
     */
    public function getModuleOnlyByWidgetId($widgetId)
    {
        return $this->query(null, ['widgetId' => $widgetId, 'moduleOnly' => 1]);
    }

    /**
     * Query Media Linked to Widgets
     * @param array $sortOrder
     * @param array $filterBy
     * @return array[int]
     */
    public function query($sortOrder = null, $filterBy = [])
    {
        if ($this->getSanitizer()->getInt('moduleOnly', $filterBy) === 1) {
            $sql = '
                SELECT lkwidgetmedia.mediaId 
                  FROM `lkwidgetmedia` 
                    INNER JOIN `media` 
                    ON `media`.mediaId = `lkwidgetmedia`.mediaId 
                   WHERE widgetId = :widgetId 
                    AND `lkwidgetmedia`.mediaId <> 0 
                    AND `media`.type = \'module\'
                ';
        } else {
            $sql = 'SELECT mediaId FROM `lkwidgetmedia` WHERE widgetId = :widgetId AND mediaId <> 0 ';
        }

        return array_map(function($element) { return $element['mediaId']; }, $this->getStore()->select($sql, array('widgetId' => $this->getSanitizer()->getInt('widgetId', $filterBy))));
    }
}