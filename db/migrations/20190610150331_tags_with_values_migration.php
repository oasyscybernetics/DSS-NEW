<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class TagsWithValuesMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $tagTable = $this->table('tag');

        // add new columns to the tag table
        if (!$tagTable->hasColumn('isSystem')) {

            $tagTable
                ->addColumn('isSystem', 'integer', ['default' => 0, 'null' => false])
                ->addColumn('options', 'text', ['default' => null, 'null' => true])
                ->addColumn('isRequired', 'integer', ['default' => 0, 'null' => false])
                ->save();
        }

        // set isSystem flag on these tags
        $this->execute('UPDATE `tag` SET `isSystem` = 1 WHERE tag IN (\'template\', \'background\', \'thumbnail\', \'imported\')');

        // add value column to lktag tables
        $lktagTables = ["lktagcampaign", "lktagdisplaygroup", "lktaglayout", "lktagmedia", "lktagplaylist"];

        foreach ($lktagTables as $lktagTable) {
            $table = $this->table($lktagTable);

            if(!$table->hasColumn('value')) {
                $table
                    ->addColumn('value', 'text', ['default' => null, 'null' => true])
                    ->save();
            }
        }
    }
}
