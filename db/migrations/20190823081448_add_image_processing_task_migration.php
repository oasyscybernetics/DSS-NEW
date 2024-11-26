<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddImageProcessingTaskMigration
 */
class AddImageProcessingTaskMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $table = $this->table('task');
        $table->insert([
            [
                'name' => 'Image Processing',
                'class' => '\Xibo\XTR\ImageProcessingTask',
                'options' => '[]',
                'schedule' => '*/5 * * * * *',
                'isActive' => '1',
                'configFile' => '/tasks/image-processing.task'
            ],
        ])->save();
    }
}
