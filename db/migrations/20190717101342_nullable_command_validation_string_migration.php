<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class NullableCommandValidationStringMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('lkcommanddisplayprofile');
        $table->changeColumn('validationString', 'string', ['limit' => 1000, 'null' => true])->save();
    }
}
