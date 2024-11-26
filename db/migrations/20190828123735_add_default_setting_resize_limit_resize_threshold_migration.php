<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddDefaultSettingResizeLimitResizeThresholdMigration
 */
class AddDefaultSettingResizeLimitResizeThresholdMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add a setting allowing users to maximum image resizing to 1920
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'DEFAULT_RESIZE_LIMIT\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'DEFAULT_RESIZE_LIMIT',
                    'value' => 6000,
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])->save();
        }

        // Add a setting allowing users set the limit to identify a large image dimensions
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'DEFAULT_RESIZE_THRESHOLD\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'DEFAULT_RESIZE_THRESHOLD',
                    'value' => 1920,
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])->save();
        }
    }
}

