<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddIsActiveReportScheduleMigration
 */
class AddIsActiveReportScheduleMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('reportschedule');
        $table
            ->addColumn('isActive', 'integer', ['default' => 1, 'after' => 'userId'])
            ->save();
    }
}