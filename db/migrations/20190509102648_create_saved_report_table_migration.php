<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

/**
 * Class CreateSavedReportTableMigration
 */
class CreateSavedReportTableMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('saved_report', ['id' => 'savedReportId']);
        $table->addColumn('saveAs', 'string')
            ->addColumn('reportName', 'string')
            ->addColumn('mediaId', 'integer')
            ->addColumn('reportScheduleId', 'integer')
            ->addColumn('generatedOn', 'integer')
            ->addColumn('userId', 'integer')
            ->addForeignKey('userId', 'user', 'userId')
            ->addForeignKey('mediaId', 'media', 'mediaId')
            ->addForeignKey('reportScheduleId', 'reportschedule', 'reportScheduleId')
            ->save();
    }
}