<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class InstallSavedReportsAndSpacerModulesMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $modules = $this->table('module');

        if (!$this->fetchRow('SELECT * FROM module WHERE module = \'savedreport\'')) {
            $modules->insert([
                'module' => 'savedreport',
                'name' => 'Saved Reports',
                'enabled' => 1,
                'regionSpecific' => 0,
                'description' => 'A saved report to be stored in the library',
                'schemaVersion' => 1,
                'previewEnabled' => 0,
                'assignable' => 0,
                'render_as' => null,
                'class' => 'Xibo\Widget\SavedReport',
                'defaultDuration' => 10,
                'validExtensions' => 'json',
                'installName' => 'savedreport'
            ])->save();
        }

        if (!$this->fetchRow('SELECT * FROM module WHERE module = \'spacer\'')) {
            $modules->insert([
                'module' => 'spacer',
                'name' => 'Spacer',
                'enabled' => 1,
                'regionSpecific' => 1,
                'description' => 'Make a Region empty for a specified duration',
                'schemaVersion' => 1,
                'previewEnabled' => 0,
                'assignable' => 1,
                'render_as' => 'html',
                'class' => 'Xibo\Widget\Spacer',
                'defaultDuration' => 60,
                'validExtensions' => null,
                'installName' => 'spacer'
            ])->save();
        }
    }
}
