<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

/**
 * Class AddDefaultSettingQuickChartMigration
 */
class AddDefaultSettingQuickChartMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add a setting to allow report converted to pdf
        if (!$this->fetchRow('SELECT * FROM `setting` WHERE setting = \'QUICK_CHART_URL\'')) {
            $this->table('setting')->insert([
                [
                    'setting' => 'QUICK_CHART_URL',
                    'value' => 'https://quickchart.io',
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])->save();
        }
    }
}


