<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

class DatasetAddCustomHeadersColumnMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $dataSetTable = $this->table('dataset');

        if (!$dataSetTable->hasColumn('customHeaders')) {
            $dataSetTable
                ->addColumn('customHeaders', 'text', ['null' => true, 'default' => null, 'after' => 'password'])
                ->save();
        }
    }
}
