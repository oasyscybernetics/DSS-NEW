<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

/**
 * Class AddLinuxDisplayProfileMigration
 */
class AddLinuxDisplayProfileMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Check to see if we already have a Linux default display profile. if not, add it.
        if (!$this->fetchRow('SELECT * FROM displayprofile WHERE type = \'linux\' AND isDefault = 1')) {
            // Get system user
            $user = $this->fetchRow('SELECT userId FROM `user` WHERE userTypeId = 1');

            $table = $this->table('displayprofile');
            $table->insert([
                'name' => 'Linux',
                'type' => 'linux',
                'config' => '[]',
                'userId' => $user['userId'],
                'isDefault' => 1
            ])->save();
        }
    }
}
