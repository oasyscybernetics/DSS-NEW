<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddBandwidthLimitColumnToDisplaygroupMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $displayGroupTable = $this->table('displaygroup');

        if (!$displayGroupTable->hasColumn('bandwidthLimit')) {
            $displayGroupTable
                ->addColumn('bandwidthLimit', 'integer', ['default' => 0, 'null' => false])
                ->save();
        }
    }
}
