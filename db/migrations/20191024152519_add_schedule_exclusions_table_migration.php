<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddScheduleExclusionsTableMigration
 */

class AddScheduleExclusionsTableMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('scheduleexclusions', ['id' => 'scheduleExclusionId']);
        $table
            ->addColumn('eventId', 'integer')
            ->addColumn('fromDt', 'integer')
            ->addColumn('toDt', 'integer')
            ->addForeignKey('eventId', 'schedule', 'eventId')
            ->save();
    }
}