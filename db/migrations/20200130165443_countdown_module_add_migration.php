<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class CountdownModuleAddMigration
 */
class CountdownModuleAddMigration extends AbstractMigration
{
    /** @inheritDoc */
    public function change()
    {
        if (!$this->fetchRow('SELECT * FROM module WHERE module = \'countdown\'')) {
            $modules = $this->table('module');
            $modules->insert([
                'module' => 'countdown',
                'name' => 'Countdown',
                'enabled' => 1,
                'regionSpecific' => 1,
                'description' => 'Countdown Module',
                'schemaVersion' => 1,
                'previewEnabled' => 1,
                'assignable' => 1,
                'render_as' => 'html',
                'viewPath' => '../modules',
                'class' => 'Xibo\Widget\Countdown',
                'defaultDuration' => 60,
                'installName' => 'countdown'
            ])->save();
        }
    }
}
