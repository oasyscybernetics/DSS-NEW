<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddPreviousRunDateReportScheduleMigration
 */
class ChangeStatTableStartEndColumnMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('stat');
        $table->rename('stat_archive');
    }
}