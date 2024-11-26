<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

class GeoScheduleMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $scheduleTable = $this->table('schedule');

        // Add new columns to Schedule table - isGeoAware and geoLocation
        if (!$scheduleTable->hasColumn('isGeoAware')) {
            $scheduleTable
                ->addColumn('isGeoAware', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 0])
                ->addColumn('geoLocation', 'text', ['default' => null, 'null' => true])
                ->save();
        }
    }
}
