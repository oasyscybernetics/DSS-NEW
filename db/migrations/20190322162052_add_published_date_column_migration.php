<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddPublishedDateColumnMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $layoutTable = $this->table('layout');

        if (!$layoutTable->hasColumn('publishedDate')) {
            $this->execute('UPDATE `layout` SET createdDt = \'1970-01-01 00:00:00\' WHERE createdDt < \'2000-01-01\'');
            $this->execute('UPDATE `layout` SET modifiedDt = \'1970-01-01 00:00:00\' WHERE modifiedDt < \'2000-01-01\'');

            $layoutTable
                ->changeColumn('createdDt', 'datetime', ['null' => true, 'default' => null])
                ->changeColumn('modifiedDt', 'datetime', ['null' => true, 'default' => null])
                ->addColumn('publishedDate', 'datetime', ['null' => true, 'default' => null])
                ->save();
        }
    }
}
