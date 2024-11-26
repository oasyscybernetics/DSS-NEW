<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class MissingDefaultValueMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('dataset');
        $table->changeColumn('lastDataEdit', 'integer', ['default' => 0])
            ->changeColumn('lastClear', 'integer', ['default' => 0])
            ->save();
    }
}
