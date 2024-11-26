<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class ForgottenPasswordReminderMigration
 */
class ForgottenPasswordReminderMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'DEFAULT_USERGROUP\'')) {
            $this->execute('
                INSERT INTO `setting` (`setting`, `value`, `fieldType`, `helptext`, `options`, `cat`, `userChange`, `title`, `validation`, `ordering`, `default`, `userSee`, `type`) 
                  VALUES (\'DEFAULT_USERGROUP\', \'1\', \'text\', \'The default User Group for new Users\', \'1\', \'users\', 1, \'Default User Group\', \'\', 4, \'\', 1, \'int\');
            ');
        }

        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'PASSWORD_REMINDER_ENABLED\'')) {
            $this->execute('
                INSERT INTO `setting` (`setting`, `value`, `fieldType`, `helptext`, `options`, `cat`, `userChange`, `title`, `validation`, `ordering`, `default`, `userSee`, `type`) 
                  VALUES (\'PASSWORD_REMINDER_ENABLED\', \'Off\', \'dropdown\', \'Is password reminder enabled?\', \'On|On except Admin|Off\', \'users\', 1, \'Password Reminder\', \'\', 50, \'Off\', 1, \'string\');
            ');
        }

        $table = $this->table('user');
        if (!$table->hasColumn('isPasswordChangeRequired')) {
            $table
                ->addColumn('isPasswordChangeRequired', 'integer',
                    ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 0])
                ->save();
        }
    }
}
