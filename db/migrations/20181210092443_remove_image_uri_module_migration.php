<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class RemoveImageUriModuleMigration
 * Remove the imageUri column from modules table
 */
class RemoveImageUriModuleMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('module');
        if ($table->hasColumn('imageUri')) {
            $table->removeColumn('imageUri')->update();
        }
    }
}
