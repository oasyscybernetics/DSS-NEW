<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddEngagementsStatsMigration
 */
class AddEngagementsStatsMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('stat');
        $table
            ->addColumn('engagements', 'text', ['default' => null, 'null' => true])
            ->save();
    }
}
