<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddPlaylistEnableStatMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $playlistTable = $this->table('playlist');

        if (!$playlistTable->hasColumn('enableStat')) {
            $this->execute('UPDATE `playlist` SET createdDt = \'1970-01-01 00:00:00\' WHERE createdDt < \'2000-01-01\'');
            $this->execute('UPDATE `playlist` SET modifiedDt = \'1970-01-01 00:00:00\' WHERE modifiedDt < \'2000-01-01\'');

            $playlistTable
                ->changeColumn('createdDt', 'datetime', ['null' => true, 'default' => null])
                ->changeColumn('modifiedDt', 'datetime', ['null' => true, 'default' => null])
                ->addColumn('enableStat', 'string', ['null' => true])
                ->save();
        }

        $settingsTable = $this->table('setting');

        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'PLAYLIST_STATS_ENABLED_DEFAULT\'')) {
            $settingsTable
                ->insert([
                    [
                        'setting' => 'PLAYLIST_STATS_ENABLED_DEFAULT',
                        'value' => 'Inherit',
                        'userSee' => 1,
                        'userChange' => 1
                    ]
                ])
                ->save();
        }
    }
}
