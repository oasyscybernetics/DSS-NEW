<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddCommandEntityToPermissionEntityMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $permissionEntity = $this->table('permissionentity');
        $permissionEntity
            ->insert([
                ['entity' => 'Xibo\Entity\Command']
            ])
            ->save();
    }
}
