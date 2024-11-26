<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class FixDuplicateModuleFilesMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // get System User
        $getSystemUserQuery = $this->query('SELECT userId FROM user WHERE userTypeId = 1 ORDER BY userId LIMIT 1 ');
        $getSystemUserResult = $getSystemUserQuery->fetchAll(PDO::FETCH_ASSOC);

        $userId = $getSystemUserResult[0]['userId'];

        // set System User as owner of the module files
        $this->execute('UPDATE `media` SET userId = ' . $userId . ' WHERE moduleSystemFile = 1 AND userId = 0; ');
    }
}
