<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddReportPageMigration
 */
class AddReportPageMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $pages = $this->table('pages');

        // add report page
        if (!$this->fetchRow('SELECT * FROM pages WHERE name = \'report\'')) {
            $pages->insert([
                'name' => 'report',
                'title' => 'Report',
                'asHome' => 0
            ])->save();
        }
    }
}