<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class DataSetRssMigration
 */
class DataSetRssMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        if (!$this->hasTable('datasetrss')) {
            $table = $this->table('datasetrss');
            $table
                ->addColumn('dataSetId', 'integer')
                ->addColumn('psk', 'string', ['limit' => 254])
                ->addColumn('title', 'string', ['limit' => 254])
                ->addColumn('author', 'string', ['limit' => 254])
                ->addColumn('titleColumnId', 'integer', ['null' => true, 'default' => null])
                ->addColumn('summaryColumnId', 'integer', ['null' => true, 'default' => null])
                ->addColumn('contentColumnId', 'integer', ['null' => true, 'default' => null])
                ->addColumn('publishedDateColumnId', 'integer', ['null' => true, 'default' => null])
                ->addColumn('sort', 'text', ['null' => true, 'default' => null])
                ->addColumn('filter', 'text', ['null' => true, 'default' => null])
                ->addForeignKey('dataSetId', 'dataset', 'dataSetId')
                ->save();
        }
    }
}
