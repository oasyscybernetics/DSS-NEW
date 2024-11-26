<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddHorizontalMenuSettingMigration
 */
class AddHorizontalMenuSettingMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add a setting allowing users enable event sync on applicable events
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'NAVIGATION_MENU_POSITION\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'NAVIGATION_MENU_POSITION',
                    'value' => 'vertical',
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])->save();
        }
    }
}
