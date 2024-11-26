<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

namespace Xibo\Factory;

use Xibo\Entity\ScheduleExclusion;
use Xibo\Service\LogServiceInterface;
use Xibo\Service\SanitizerServiceInterface;
use Xibo\Storage\StorageServiceInterface;

/**
 * Class ScheduleExclusionFactory
 * @package Xibo\Factory
 */
class ScheduleExclusionFactory extends BaseFactory
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
     * Load by Event Id
     * @param int $eventId
     * @return array[ScheduleExclusion]
     */
    public function getByEventId($eventId)
    {
        return $this->query(null, array('eventId' => $eventId));
    }

    /**
     * Create Empty
     * @return ScheduleExclusion
     */
    public function createEmpty()
    {
        return new ScheduleExclusion($this->getStore(), $this->getLog());
    }

    /**
     * Create a schedule exclusion
     * @param int $eventId
     * @param int $fromDt
     * @param int $toDt
     * @return ScheduleExclusion
     */
    public function create($eventId, $fromDt, $toDt)
    {
        $scheduleExclusion = $this->createEmpty();
        $scheduleExclusion->eventId = $eventId;
        $scheduleExclusion->fromDt = $fromDt;
        $scheduleExclusion->toDt = $toDt;

        return $scheduleExclusion;
    }

    /**
     * Query Schedule exclusions
     * @param array $sortOrder
     * @param array $filterBy
     * @return array[ScheduleExclusion]
     */
    public function query($sortOrder = null, $filterBy = [])
    {
        $entries = array();

        $sql = 'SELECT * FROM `scheduleexclusions` WHERE eventId = :eventId';

        foreach ($this->getStore()->select($sql, array('eventId' => $this->getSanitizer()->getInt('eventId', $filterBy))) as $row) {
            $entries[] = $this->createEmpty()->hydrate($row);
        }

        return $entries;
    }
}