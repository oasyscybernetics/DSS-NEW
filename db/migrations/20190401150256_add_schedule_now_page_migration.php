<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class AddScheduleNowPageMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $pages = $this->table('pages');

        // add schedule now page
        if (!$this->fetchRow('SELECT * FROM pages WHERE name = \'schedulenow\'')) {
            $pages->insert([
                'name' => 'schedulenow',
                'title' => 'Schedule Now',
                'asHome' => 0
            ])->save();
        }

        // add permission to the schedule now page to every group and user, excluding "Everyone"
        $permissions = $this->table('permission');
        $scheduleNowPageId = $this->fetchRow('SELECT pageId FROM `pages` WHERE `name` = \'schedulenow\' ');
        $groupIds = $this->fetchAll('SELECT groupId FROM `group` WHERE `isEveryone` = 0 ');

        foreach ($groupIds as $groupId) {
            $permissions->insert([
                [
                    'entityId' => 1,
                    'groupId' => $groupId['groupId'],
                    'objectId' => $scheduleNowPageId[0],
                    'view' => 1,
                    'edit' => 0,
                    'delete' => 0
                ]
            ])->save();
        }
    }
}
