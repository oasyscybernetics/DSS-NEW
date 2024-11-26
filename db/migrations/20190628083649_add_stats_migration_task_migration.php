<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddStatsMigrationTaskMigration
 */
class AddStatsMigrationTaskMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('task');
        if (!$this->fetchRow('SELECT * FROM `task` WHERE name = \'Statistics Migration\'')) {
            $table->insert([
                [
                    'name' => 'Statistics Migration',
                    'class' => '\Xibo\XTR\StatsMigrationTask',
                    'options' => '{"killSwitch":"0","numberOfRecords":"5000","numberOfLoops":"10","pauseBetweenLoops":"1","optimiseOnComplete":"1"}',
                    'schedule' => '*/10 * * * * *',
                    'isActive' => '1',
                    'configFile' => '/tasks/stats-migration.task'
                ],
            ])->save();
        }
    }
}
