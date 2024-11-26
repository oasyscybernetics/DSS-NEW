<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

namespace Xibo\Storage;

/**
 * Class TimeSeriesMongoDbResults
 * @package Xibo\Storage
 */
class TimeSeriesMongoDbResults implements TimeSeriesResultsInterface
{
    /**
     * Statement
     * @var \MongoDB\Driver\Cursor
     */
    private $object;

    /**
     * Total number of stats
     */
    public $totalCount;

    /**
     * Iterator
     * @var \IteratorIterator
     */
    private $iterator;

    /**
     * @inheritdoc
     */
    public function __construct($cursor = null)
    {
        $this->object = $cursor;
    }

    /** @inheritdoc */
    public function getArray()
    {
        $result = $this->object->toArray();

        $rows = [];

        foreach ($result as $row) {

            $entry = [];

            $entry['id'] = $row['id'];
            $entry['type'] = $row['type'];
            $entry['start'] = $row['start']->toDateTime()->format('U');
            $entry['end'] = $row['end']->toDateTime()->format('U');
            $entry['display'] = isset($row['display']) ? $row['display']: 'No display';
            $entry['layout'] = isset($row['layout']) ? $row['layout']: 'No layout';
            $entry['media'] = isset($row['media']) ? $row['media'] : 'No media' ;
            $entry['tag'] = $row['tag'];
            $entry['duration'] = $row['duration'];
            $entry['count'] = $row['count'];
            $entry['displayId'] = isset($row['displayId']) ? $row['displayId']: 0;
            $entry['layoutId'] = isset($row['layoutId']) ? $row['layoutId']: 0;
            $entry['campaignId'] = isset($row['campaignId']) ? $row['campaignId']: 0;
            $entry['widgetId'] = isset($row['widgetId']) ? $row['widgetId']: 0;
            $entry['mediaId'] = isset($row['mediaId']) ? $row['mediaId']: 0;
            $entry['statDate'] = isset($row['statDate']) ? $row['statDate']->toDateTime()->format('U') : null;
            $entry['engagements'] = isset($row['engagements']) ? $row['engagements'] : [];
            $entry['tagFilter'] = isset($row['tagFilter']) ? $row['tagFilter'] : [
                'dg' => [],
                'layout' => [],
                'media' => []
            ];

            $rows[] = $entry;
        }

        return ['statData'=> $rows];

    }

    public function getIterator()
    {
        if ($this->iterator == null) {
            $this->iterator = new \IteratorIterator($this->object);
            $this->iterator->rewind();
        }

        return $this->iterator;
    }

    /** @inheritdoc */
    public function getNextRow()
    {

        $this->getIterator();

        if ($this->iterator->valid()) {

            $document = $this->iterator->current();
            $this->iterator->next();

            return  (array) $document;
        }

        return false;

    }

    /** @inheritdoc */
    public function getTotalCount()
    {
        return $this->totalCount;
    }
}