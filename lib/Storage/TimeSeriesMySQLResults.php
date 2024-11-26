<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

namespace Xibo\Storage;

/**
 * Class TimeSeriesMySQLResults
 * @package Xibo\Storage
 */
class TimeSeriesMySQLResults implements TimeSeriesResultsInterface
{
    /**
     * Statement
     * @var \PDOStatement
     */
    private $object;

    /**
     * Total number of stats
     */
    public $totalCount;

    /**
     * @inheritdoc
     */
    public function __construct($stmtObject = null)
    {
        $this->object = $stmtObject;
    }

    /**
     * @inheritdoc
     */
    public function getArray()
    {
        $rows = [];

        while ($row = $this->object->fetch(\PDO::FETCH_ASSOC)) {

            $entry = [];

            // Read the columns
            $entry['id'] = $row['statId'];
            $entry['type'] = $row['type'];
            $entry['start'] = $row['start'];
            $entry['end'] = $row['end'];
            $entry['layout'] = $row['layout'];
            $entry['display'] = $row['display'];
            $entry['media'] = $row['media'];
            $entry['tag'] = $row['tag'];
            $entry['duration'] = $row['duration'];
            $entry['count'] = $row['count'];
            $entry['displayId'] = $row['displayId'];
            $entry['layoutId'] = $row['layoutId'];
            $entry['widgetId'] = $row['widgetId'];
            $entry['mediaId'] = $row['mediaId'];
            $entry['campaignId'] = $row['campaignId'];
            $entry['statDate'] = $row['statDate'];
            $entry['engagements'] = isset($row['engagements']) ? json_decode($row['engagements']) : [];

            // Tags
            // Mimic the structure we have in Mongo.
            $entry['tagFilter'] = [
                'dg' => [],
                'layout' => [],
                'media' => []
            ];

            // Display Tags
            if (array_key_exists('displayTags', $row) && !empty($row['displayTags'])) {
                $tags = explode(',', $row['displayTags']);
                foreach ($tags as $tag) {
                    $tag = explode('|', $tag);
                    $value = $tag[1] ?? null;
                    $entry['tagFilter']['dg'][] = [
                        'tag' => $tag[0],
                        'value' => ($value === 'null') ? null : $value
                    ];
                }
            }

            // Layout Tags
            if (array_key_exists('layoutTags', $row) && !empty($row['layoutTags'])) {
                $tags = explode(',', $row['layoutTags']);
                foreach ($tags as $tag) {
                    $tag = explode('|', $tag);
                    $value = $tag[1] ?? null;
                    $entry['tagFilter']['layout'][] = [
                        'tag' => $tag[0],
                        'value' => ($value === 'null') ? null : $value
                    ];
                }
            }

            // Media Tags
            if (array_key_exists('mediaTags', $row) && !empty($row['mediaTags'])) {
                $tags = explode(',', $row['mediaTags']);
                foreach ($tags as $tag) {
                    $tag = explode('|', $tag);
                    $value = $tag[1] ?? null;
                    $entry['tagFilter']['media'][] = [
                        'tag' => $tag[0],
                        'value' => ($value === 'null') ? null : $value
                    ];
                }
            }

            $rows[] = $entry;
        }

        return ['statData'=> $rows];

    }

    /**
     * @inheritdoc
     */
    public function getNextRow()
    {
        return $this->object->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @inheritdoc
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

}