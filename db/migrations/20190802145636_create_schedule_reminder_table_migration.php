<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class CreateScheduleReminderTableMigration
 */
class CreateScheduleReminderTableMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {

        // Create table
        $table = $this->table('schedulereminder', ['id' => 'scheduleReminderId']);
        $table

            ->addColumn('eventId', 'integer')
            ->addColumn('value', 'integer')
            ->addColumn('type', 'integer')
            ->addColumn('option', 'integer')
            ->addColumn('reminderDt', 'integer')
            ->addColumn('lastReminderDt', 'integer')
            ->addColumn('isEmail', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
            ->addForeignKey('eventId', 'schedule', 'eventId')
            ->save();


    }
}