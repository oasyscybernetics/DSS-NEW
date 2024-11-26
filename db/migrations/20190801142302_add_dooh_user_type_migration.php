<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

class AddDoohUserTypeMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $userTypeTable = $this->table('usertype');

        if (!$this->fetchRow('SELECT * FROM usertype WHERE `userType` = \'DOOH\'')) {
            $userTypeTable->insert([
                'userTypeId' => 4,
                'userType' => 'DOOH'
            ])->save();
        }
    }
}
