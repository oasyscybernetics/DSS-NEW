<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class PlayerSoftwareVersionFieldMigration
 */
class PlayerSoftwareVersionFieldMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('player_software');

        if (!$table->hasColumn('playerShowVersion')) {
            $table
                ->addColumn('playerShowVersion', 'string', ['limit' => 50])
                ->save();
        }
    }
}
