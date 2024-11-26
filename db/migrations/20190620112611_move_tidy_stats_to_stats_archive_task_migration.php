<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class MoveTidyStatsToStatsArchiveTaskMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // query the database and look for Stats Archive task
        $statsArchiveQuery = $this->query('SELECT taskId, name, options, isActive FROM `task` WHERE `name` = \'Stats Archive\' ;');
        $statsArchiveData = $statsArchiveQuery->fetchAll(PDO::FETCH_ASSOC);

        if (count($statsArchiveData) > 0) {
            foreach ($statsArchiveData as $row) {
                $taskId = $row['taskId'];
                $isActive = $row['isActive'];
                $options = json_decode($row['options']);

                // if the task is current set as Active, we need to ensure that archiveStats option is set to On (default is Off)
                if ($isActive == 1) {
                    $options->archiveStats = 'On';
                } else {
                    $options->archiveStats = 'Off';
                }

                // save updated options to variable
                $newOptions = json_encode($options);

                $this->execute('UPDATE `task` SET isActive = 1, options = \'' . $newOptions . '\' WHERE taskId = '. $taskId );
            }
        }
    }
}
