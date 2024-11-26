<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Entity;


use Xibo\Helper\Random;
use Xibo\Service\LogServiceInterface;
use Xibo\Storage\StorageServiceInterface;

/**
 * Class DataSetRss
 * @package Xibo\Entity
 *
 * @SWG\Definition()
 */
class DataSetRss implements \JsonSerializable
{
    use EntityTrait;

    public $id;
    public $dataSetId;
    public $titleColumnId;
    public $summaryColumnId;
    public $contentColumnId;
    public $publishedDateColumnId;

    public $psk;
    public $title;
    public $author;

    public $sort;
    public $filter;

    /**
     * Entity constructor.
     * @param StorageServiceInterface $store
     * @param LogServiceInterface $log
     */
    public function __construct($store, $log)
    {
        $this->setCommonDependencies($store, $log);
    }

    /**
     * @return array|mixed
     */
    public function getFilter()
    {
        return ($this->filter == '') ? ['filter' => '', 'useFilteringClause' => 0, 'filterClauses' => []] : json_decode($this->filter, true);
    }

    /**
     * @return array|mixed
     */
    public function getSort()
    {
        return ($this->sort == '') ? ['sort' => '', 'useOrderingClause' => 0, 'orderClauses' => []] : json_decode($this->sort, true);
    }

    /**
     * Save
     */
    public function save()
    {
        if ($this->id == null) {
            $this->add();

            $this->audit($this->id, 'Added', []);
        } else {
            $this->edit();

            $this->audit($this->id, 'Saved');
        }
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function setNewPsk()
    {
        $this->psk = Random::generateString(12);
        return $this;
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->getStore()->update('DELETE FROM `datasetrss` WHERE id = :id', ['id' => $this->id]);

        $this->audit($this->id, 'Deleted');
    }

    private function add()
    {
        $this->id = $this->getStore()->insert('
            INSERT INTO datasetrss (dataSetId, psk, title, author, titleColumnId, summaryColumnId, contentColumnId, publishedDateColumnId, sort, filter) VALUES 
             (:dataSetId, :psk, :title, :author, :titleColumnId, :summaryColumnId, :contentColumnId, :publishedDateColumnId, :sort, :filter)
        ', [
            'dataSetId' => $this->dataSetId,
            'psk' => $this->psk,
            'title' => $this->title,
            'author' => $this->author,
            'titleColumnId' => $this->titleColumnId,
            'summaryColumnId' => $this->summaryColumnId,
            'contentColumnId' => $this->contentColumnId,
            'publishedDateColumnId' => $this->publishedDateColumnId,
            'sort' => $this->sort,
            'filter' => $this->filter
        ]);
    }

    private function edit()
    {
        $this->getStore()->update('
            UPDATE `datasetrss` SET
                psk = :psk,
                title = :title,
                author = :author,
                titleColumnId = :titleColumnId,
                summaryColumnId = :summaryColumnId,
                contentColumnId = :contentColumnId,
                publishedDateColumnId = :publishedDateColumnId,
                sort = :sort,
                filter = :filter
             WHERE id = :id
        ', [
            'id' => $this->id,
            'psk' => $this->psk,
            'title' => $this->title,
            'author' => $this->author,
            'titleColumnId' => $this->titleColumnId,
            'summaryColumnId' => $this->summaryColumnId,
            'contentColumnId' => $this->contentColumnId,
            'publishedDateColumnId' => $this->publishedDateColumnId,
            'sort' => $this->sort,
            'filter' => $this->filter
        ]);
    }
}