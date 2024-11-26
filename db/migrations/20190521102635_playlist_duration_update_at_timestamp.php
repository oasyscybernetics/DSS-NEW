<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class PlaylistDurationUpdateAtTimestamp
 */
class PlaylistDurationUpdateAtTimestamp extends AbstractMigration
{
    /** @inheritDoc */
    public function change()
    {
        $this->execute('UPDATE `playlist` SET createdDt = \'1970-01-01 00:00:00\' WHERE createdDt < \'2000-01-01\'');
        $this->execute('UPDATE `playlist` SET modifiedDt = \'1970-01-01 00:00:00\' WHERE modifiedDt < \'2000-01-01\'');

        $table = $this->table('playlist');
        $table
            ->changeColumn('createdDt', 'datetime', ['null' => true, 'default' => null])
            ->changeColumn('modifiedDt', 'datetime', ['null' => true, 'default' => null])
            ->changeColumn('requiresDurationUpdate', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_REGULAR, 'default' => 0, 'null' => false])
            ->save();
    }
}
