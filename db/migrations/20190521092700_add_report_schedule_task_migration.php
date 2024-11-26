<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddReportScheduleTaskMigration
 */
class AddReportScheduleTaskMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('task');
        if (!$this->fetchRow('SELECT * FROM `task` WHERE name = \'Report Schedule\'')) {
            $table->insert([
                [
                    'name' => 'Report Schedule',
                    'class' => '\Xibo\XTR\ReportScheduleTask',
                    'options' => '[]',
                    'schedule' => '*/5 * * * * *',
                    'isActive' => '1',
                    'configFile' => '/tasks/report-schedule.task'
                ],
            ])->save();
        }
    }
}
