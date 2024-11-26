<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddCommercialLicenceColumnMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $displayTable = $this->table('display');

        // Add new column to Display table - commercialLicence. 0 - Not licensed, 1 - licensed, 2 - trial licence, 3 - not applicable
        if (!$displayTable->hasColumn('commercialLicence')) {
            $displayTable
                ->addColumn('commercialLicence', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => null, 'null' => true])
                ->save();
        }
    }
}
