<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddSettingForAutoLayoutPublishMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add a setting allowing users to set the Layout to be automatically published 30 min after last change to the Layout was made
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'DEFAULT_LAYOUT_AUTO_PUBLISH_CHECKB\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'DEFAULT_LAYOUT_AUTO_PUBLISH_CHECKB',
                    'value' => 0,
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])->save();
        }

    }
}
