<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class RemoveFinanceModuleMigration
 */
class RemoveFinanceModuleMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Delete the finance module from the modules table.
        $this->execute('DELETE FROM module WHERE `module` = \'finance\'');
    }
}
