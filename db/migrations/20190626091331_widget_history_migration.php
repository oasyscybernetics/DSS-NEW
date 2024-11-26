<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class WidgetHistoryMigration
 */
class WidgetHistoryMigration extends AbstractMigration
{
    /** @inheritDoc */
    public function change()
    {
        $table = $this->table('widgethistory');
        $table
            ->addColumn('layoutHistoryId', 'integer')
            ->addColumn('widgetId', 'integer')
            ->addColumn('mediaId', 'integer', ['null' => true])
            ->addColumn('type', 'string', ['limit' => 50])
            ->addColumn('name', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addForeignKey('layoutHistoryId', 'layouthistory', 'layoutHistoryId')
            ->save();
    }
}
