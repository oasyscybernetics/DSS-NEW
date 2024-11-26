<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddReportScheduleMessageMigration
 */
class AddReportScheduleMessageMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('reportschedule');
        $table
            ->addColumn('message', 'string', ['null' => true, 'default' => null])
            ->save();
    }
}