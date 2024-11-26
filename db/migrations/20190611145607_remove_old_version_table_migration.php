<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class RemoveOldVersionTableMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        if ($this->hasTable('version')) {
            $this->execute('DROP TABLE version');
        }

    }
}
