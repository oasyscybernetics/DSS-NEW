<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddPreviousRunDateReportScheduleMigration
 */
class AddPreviousRunDateReportScheduleMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('reportschedule');
        $table
            ->addColumn('previousRunDt', 'integer', ['default' => 0, 'after' => 'lastRunDt'])
            ->addColumn('lastSavedReportId', 'integer', ['default' => 0, 'after' => 'schedule'])
            ->save();
    }
}