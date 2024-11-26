<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

class DisplayMoveCmsMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $displayTable = $this->table('display');

        // Add a two new columns to Display table, newCmsAddress and newCmsKey
        if (!$displayTable->hasColumn('newCmsAddress')) {
            $displayTable
                ->addColumn('newCmsAddress', 'string', ['limit' => 40, 'default' => null, 'null' => true])
                ->addColumn('newCmsKey', 'string', ['limit' => 40, 'default' => null, 'null' => true])
                ->save();
        }
    }
}
