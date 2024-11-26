<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

class AddSettingForTransitionAutoApplyToLayoutMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add a setting for default value of layout->autoApplyTransitions checkbox
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'DEFAULT_TRANSITION_AUTO_APPLY\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'DEFAULT_TRANSITION_AUTO_APPLY',
                    'value' => 0,
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])->save();
        }

        $layoutTable = $this->table('layout');

        // Add a new column to Layout table - autoApplyTransitions
        if (!$layoutTable->hasColumn('autoApplyTransitions')) {
            $this->execute('UPDATE `layout` SET createdDt = \'1970-01-01 00:00:00\' WHERE createdDt < \'2000-01-01\'');
            $this->execute('UPDATE `layout` SET modifiedDt = \'1970-01-01 00:00:00\' WHERE modifiedDt < \'2000-01-01\'');

            $layoutTable
                ->changeColumn('createdDt', 'datetime', ['null' => true, 'default' => null])
                ->changeColumn('modifiedDt', 'datetime', ['null' => true, 'default' => null])
                ->addColumn('autoApplyTransitions', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 0])
                ->save();
        }
    }
}
