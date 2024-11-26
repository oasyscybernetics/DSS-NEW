<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

/**
 * Class AddScheduleRecurrenceMonthlyRepeatsOnMigration
 */
class AddScheduleRecurrenceMonthlyRepeatsOnMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('schedule');
        $table
            ->addColumn('recurrenceMonthlyRepeatsOn', 'integer', ['default' => 0, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
            ->save();
    }
}
