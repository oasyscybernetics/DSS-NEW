<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

/**
 * Class AddScheduleReminderTaskMigration
 */
class AddScheduleReminderTaskMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('task');
            $table->insert([
                [
                    'name' => 'Schedule Reminder',
                    'class' => '\Xibo\XTR\ScheduleReminderTask',
                    'options' => '[]',
                    'schedule' => '*/5 * * * * *',
                    'isActive' => '1',
                    'configFile' => '/tasks/schedule-reminder.task'
                ],
            ])->save();
    }
}
