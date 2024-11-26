<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class EventSyncMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add a setting allowing users enable event sync on applicable events
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'EVENT_SYNC\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'EVENT_SYNC',
                    'value' => 0,
                    'userSee' => 0,
                    'userChange' => 0
                ]
            ])->save();
        }

        $scheduleTable = $this->table('schedule');

        if (!$scheduleTable->hasColumn('syncEvent')) {
            $scheduleTable
                ->addColumn('syncEvent', 'integer', ['default' => 0, 'null' => false])
                ->save();
        }
    }
}
