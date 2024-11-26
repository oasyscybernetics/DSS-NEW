<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddGlobalSettingForCascadePermissionsMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add a setting allowing users to set the default value for the cascade permission checkbox, default to 1
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'DEFAULT_CASCADE_PERMISSION_CHECKB\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'DEFAULT_CASCADE_PERMISSION_CHECKB',
                    'value' => 1,
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])->save();
        }
    }
}
