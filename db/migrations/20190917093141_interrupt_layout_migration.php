<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class InterruptLayoutMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $scheduleTable = $this->table('schedule');

        // Add a new column to Schedule table - shareOfVoice
        if (!$scheduleTable->hasColumn('shareOfVoice')) {
            $scheduleTable
                ->addColumn('shareOfVoice', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => null, 'null' => true])
                ->save();
        }
    }
}
