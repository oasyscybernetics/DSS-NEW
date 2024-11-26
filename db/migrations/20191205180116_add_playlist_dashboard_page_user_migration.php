<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddPlaylistDashboardPageUserMigration
 */
class AddPlaylistDashboardPageUserMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {

        $result = $this->fetchRow('SELECT entityId FROM `permissionentity` WHERE entity LIKE \'%Page\' LIMIT 1 ');
        $pageEntityId = $result['entityId'];

        $result = $this->fetchRow('SELECT pageId FROM `pages` WHERE `pages`.name = \'user\' LIMIT 1 ');
        $userPageId = $result['pageId'];

        $result = $this->fetchRow('SELECT pageId FROM `pages` WHERE `pages`.name = \'library\' LIMIT 1 ');
        $libraryPageId = $result['pageId'];

        // Create playlist dashboard page
        $this->execute('
            INSERT INTO `pages` 
                SET `name`=\'playlistdashboard\', `title`= \'Playlist Dashboard\', `asHome`=1;
        ');

        // Get playlist dashboard pageId
        $playlistDashboardPageId = $this->getAdapter()->getConnection()->lastInsertId();

        // Create playlist dashboard user group
        $this->execute('
            INSERT INTO `group` 
                SET `group`=\'Playlist Dashboard User\', `isUserSpecific`= 0, `isEveryone`= 0, `isSystemNotification`= 0;
        ');

        // Get playlist dashboard user groupId
        $groupId = $this->getAdapter()->getConnection()->lastInsertId();

        // Set Permission for playlist dashboard user group
        $permission = $this->table('permission');
        $permission->insert([
            [
                'entityId' => $pageEntityId,
                'groupId' => $groupId,
                'objectId' => $playlistDashboardPageId,
                'view' => 1,
                'edit' => 0,
                'delete' => 0
            ],
            [
                'entityId' => $pageEntityId,
                'groupId' => $groupId,
                'objectId' => $libraryPageId,
                'view' => 1,
                'edit' => 0,
                'delete' => 0
            ],
            [
                'entityId' => $pageEntityId,
                'groupId' => $groupId,
                'objectId' => $userPageId,
                'view' => 1,
                'edit' => 0,
                'delete' => 0
            ],
        ])->save();
    }
}

