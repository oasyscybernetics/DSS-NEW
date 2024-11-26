<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

/**
 * Class SimpleSettingsMigration
 */
class SimpleSettingsMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Update all of our old "Checked|Unchecked" boxes to be proper checkboxes
        $this->execute('UPDATE `setting` SET `value` = 0 WHERE `value` = \'Unchecked\'');
        $this->execute('UPDATE `setting` SET `value` = 1 WHERE `value` = \'Checked\'');

        // Update all of our old "Yes|No" boxes to be proper checkboxes
        $this->execute('UPDATE `setting` SET `value` = 0 WHERE `value` = \'No\'');
        $this->execute('UPDATE `setting` SET `value` = 1 WHERE `value` = \'Yes\'');

        // Update all of our old "Off|On" boxes to be proper checkboxes (unless there are more than 2 options)
        $this->execute('UPDATE `setting` SET `value` = 0 WHERE `value` = \'Off\' AND `setting` NOT IN (\'MAINTENANCE_ENABLED\', \'PASSWORD_REMINDER_ENABLED\', \'SENDFILE_MODE\')');
        $this->execute('UPDATE `setting` SET `value` = 1 WHERE `value` = \'On\' AND `setting` NOT IN (\'MAINTENANCE_ENABLED\', \'PASSWORD_REMINDER_ENABLED\')');

        $table = $this->table('setting');
        $table
            ->removeColumn('type')
            ->removeColumn('title')
            ->removeColumn('default')
            ->removeColumn('fieldType')
            ->removeColumn('helpText')
            ->removeColumn('options')
            ->removeColumn('cat')
            ->removeColumn('validation')
            ->removeColumn('ordering')
            ->save();
    }
}
