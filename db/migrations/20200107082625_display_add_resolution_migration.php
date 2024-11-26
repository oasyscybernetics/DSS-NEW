<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class DisplayAddResolutionMigration
 */
class DisplayAddResolutionMigration extends AbstractMigration
{
    /** @inheritDoc */
    public function change()
    {
        // Add orientation and resolution to the display table
        // these are informational fields intended to be updated by the Player during a NotifyStatus call
        // the Player will send the resolution as two integers of width and height, which we will combine to
        // WxH in the resolution column and use to work out the orientation.
        $display = $this->table('display');
        $display
            ->addColumn('orientation', 'string', ['limit' => 10, 'null' => true, 'default' => null])
            ->addColumn('resolution', 'string', ['limit' => 10, 'null' => true, 'default' => null])
            ->save();
    }
}
