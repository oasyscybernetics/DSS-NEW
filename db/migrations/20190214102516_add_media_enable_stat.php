<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddMediaEnableStat
 */
class AddMediaEnableStat extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $this->execute('UPDATE `media` SET createdDt = \'1970-01-01 00:00:00\' WHERE createdDt < \'2000-01-01\'');
        $this->execute('UPDATE `media` SET modifiedDt = \'1970-01-01 00:00:00\' WHERE modifiedDt < \'2000-01-01\'');

        $table = $this->table('media');
        $table
            ->changeColumn('createdDt', 'datetime', ['null' => true, 'default' => null])
            ->changeColumn('modifiedDt', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('enableStat', 'string', ['null' => true])
            ->save();
    }
}