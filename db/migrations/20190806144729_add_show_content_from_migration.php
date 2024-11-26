<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddShowContentFromMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $userTable = $this->table('user');

        if (!$userTable->hasColumn('showContentFrom')) {
            $userTable
                ->addColumn('showContentFrom', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1])
                ->save();
        }

    }
}
