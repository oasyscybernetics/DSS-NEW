<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddGlobalStatSettingMigration
 */
class AddGlobalStatSettingMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $dateTime = new \DateTime();
        $earlierMonth = $dateTime->modify( '-1 month' )->format( 'Y-m-d' );

        $result = $this->fetchRow('SELECT EXISTS (SELECT * FROM `stat` where `stat`.end >  \'' . $earlierMonth . '\' LIMIT 1)');
        $table = $this->table('setting');

        // if there are no stats recorded in last 1 month then layout stat is Off
        if ($result[0] <= 0 ) {
            $table
                ->insert([
                    [
                        'setting' => 'LAYOUT_STATS_ENABLED_DEFAULT',
                        'value' => '0',
                        'userSee' => 1,
                        'userChange' => 1
                    ]
                ])
                ->save();
        } else {
            $table
                ->insert([
                    [
                        'setting' => 'LAYOUT_STATS_ENABLED_DEFAULT',
                        'value' => '1',
                        'userSee' => 1,
                        'userChange' => 1
                    ]
                ])
                ->save();
        }


        // Media and widget stat is always set to Inherit
        $table
            ->insert([
                [
                    'setting' => 'DISPLAY_PROFILE_AGGREGATION_LEVEL_DEFAULT',
                    'value' => 'Individual',
                    'userSee' => 1,
                    'userChange' => 1
                ],
                [
                    'setting' => 'MEDIA_STATS_ENABLED_DEFAULT',
                    'value' => 'Inherit',
                    'userSee' => 1,
                    'userChange' => 1
                ],
                [
                    'setting' => 'WIDGET_STATS_ENABLED_DEFAULT',
                    'value' => 'Inherit',
                    'userSee' => 1,
                    'userChange' => 1
                ]
            ])
            ->save();
    }
}