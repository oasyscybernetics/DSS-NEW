<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class CreateLayoutHistoryTableMigration
 */
class CreateLayoutHistoryTableMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('layouthistory', ['id' => 'layoutHistoryId']);
        $table->addColumn('campaignId', 'integer')
            ->addColumn('layoutId', 'integer')
            ->addColumn('publishedDate', 'datetime', ['null' => true, 'default' => null])
            ->addForeignKey('campaignId', 'campaign', 'campaignId')
            ->create();

        // insert all published layoutIds and their corresponding campaignId in the layouthistory
        $this->execute('INSERT INTO `layouthistory` (campaignId, layoutId, publishedDate)  
                            SELECT T.campaignId, L.layoutId, L.modifiedDt
                            FROM layout L
                            INNER JOIN
                                (SELECT 
                                    lkc.layoutId, lkc.campaignId
                                FROM
                                    `campaign` C
                                INNER JOIN `lkcampaignlayout` lkc 
                                ON C.campaignId = lkc.campaignId
                                WHERE
                                    isLayoutSpecific = 1) T 
                            ON T.layoutId = L.layoutId
                            WHERE
                                L.parentId IS NULL;');
    }
}
