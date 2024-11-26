<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddSettingForDefaultTransitionTypeMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add a setting allowing users to set the default value for IN and OUT Transition type
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'DEFAULT_TRANSITION_IN\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'DEFAULT_TRANSITION_IN',
                    'value' => 'fadeIn',
                    'userSee' => 1,
                    'userChange' => 1
                ],
                [
                    'setting' => 'DEFAULT_TRANSITION_OUT',
                    'value' => 'fadeOut',
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])->save();
        }
    }
}
