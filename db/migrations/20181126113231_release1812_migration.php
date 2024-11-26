<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class Release1812Migration
 * applicable changes from 143.json
 */
class Release1812Migration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add a setting allowing users to auto authorise new displays
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'DISPLAY_AUTO_AUTH\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'DISPLAY_AUTO_AUTH',
                    'value' => 0,
                    'userSee' => 0,
                    'userChange' => 0
                ]
            ])->save();
        }

        // Rename Dashboard to Icon Dashboard
        $this->execute('UPDATE `pages` set title = \'Icon Dashboard\', name = \'icondashboard\' WHERE `name` = \'dashboard\'');

        // Change the DataSet View module name
        $this->execute('UPDATE `module` set Name = \'DataSet View\' WHERE `Module` = \'datasetview\'');

        // Add M4V extension to Video module
        if (!$this->fetchRow('SELECT * FROM `module` WHERE `module` = \'video\' AND validExtensions LIKE \'%m4v%\'')) {
            $this->execute('UPDATE `module` SET validExtensions = CONCAT(validextensions, \',m4v\') WHERE `module` = \'video\' LIMIT 1;');
        }
    }
}
