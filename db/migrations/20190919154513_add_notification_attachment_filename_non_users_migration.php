<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

/**
 * Class AddNotificationAttachmentFilenameNonUsersMigration
 */

class AddNotificationAttachmentFilenameNonUsersMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('notification');
        $table
            ->addColumn('filename', 'string', ['limit' => 1000, 'null' => true])
            ->addColumn('nonusers', 'string', ['limit' => 1000, 'null' => true])
            ->save();
    }
}
