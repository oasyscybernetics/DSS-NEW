<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddHtmlDatatypeMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        if (!$this->fetchRow('SELECT * FROM `datatype` WHERE dataType = \'HTML\'')) {
            $this->table('datatype')->insert([
                [
                    'dataTypeId' => 6,
                    'dataType' => 'HTML'
                ]
            ])->save();
        }
    }
}
