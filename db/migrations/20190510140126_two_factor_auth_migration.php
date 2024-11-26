<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class TwoFactorAuthMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $userTable = $this->table('user');

        if (!$userTable->hasColumn('twoFactorTypeId')) {
            $userTable
                ->addColumn('twoFactorTypeId', 'integer', ['default' => 0, 'null' => false])
                ->addColumn('twoFactorSecret', 'text', ['default' => NULL, 'null' => true])
                ->addColumn('twoFactorRecoveryCodes', 'text', ['default' => NULL, 'null' => true])
                ->save();
        }

        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'TWOFACTOR_ISSUER\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'TWOFACTOR_ISSUER',
                    'value' => '',
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])->save();
        }

    }
}
