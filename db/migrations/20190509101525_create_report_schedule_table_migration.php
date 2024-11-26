<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class CreateReportScheduleTableMigration
 */
class CreateReportScheduleTableMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('reportschedule', ['id' => 'reportScheduleId']);
        $table->addColumn('name', 'string')
            ->addColumn('reportName', 'string')
            ->addColumn('filterCriteria', 'text')
            ->addColumn('schedule', 'string')
            ->addColumn('lastRunDt', 'integer', ['default' => 0])
            ->addColumn('userId', 'integer')
            ->addColumn('createdDt', 'integer')
            ->addForeignKey('userId', 'user', 'userId')
            ->save();
    }
}

