<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddNotificationOriginalFilenameMigration
 */

class AddNotificationOriginalFilenameMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('notification');
        $table
            ->addColumn('originalFileName', 'string', ['limit' => 254, 'default' => null, 'null' => true])
            ->save();
    }
}

