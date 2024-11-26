<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

/**
 * Class AddForeignKeysToPermissionsMigration
 */
class AddForeignKeysToPermissionsMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        if (!$this->fetchRow('
            SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS WHERE constraint_schema=DATABASE()
                AND `table_name` = \'permission\' AND referenced_table_name = \'group\';')) {
            // Delete any records which result in a constraint failure (the records would be orphaned anyway)
            $this->execute('DELETE FROM `permission` WHERE groupId NOT IN (SELECT groupId FROM `group`)');
            // Add the constraint
            $this->execute('ALTER TABLE `permission` ADD CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`groupId`) REFERENCES `group` (`groupId`);');
        }

        if (!$this->fetchRow('
            SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS WHERE constraint_schema=DATABASE()
                AND `table_name` = \'permission\' AND referenced_table_name = \'permissionentity\';')) {
            // Delete any records which result in a constraint failure (the records would be orphaned anyway)
            $this->execute('DELETE FROM `permission` WHERE entityId NOT IN (SELECT entityId FROM `permissionentity`)');
            // Add the constraint
            $this->execute('ALTER TABLE `permission` ADD CONSTRAINT `permission_ibfk_2` FOREIGN KEY (`entityId`) REFERENCES `permissionentity` (`entityId`);');
        }

        // Index
        if (!$this->checkIndexExists('permission', ['objectId'], 0)) {
            $this->execute('CREATE INDEX permission_objectId_index ON permission (objectId);');
        }
    }

    /**
     * Check if an index exists
     * @param string $table
     * @param string[] $columns
     * @param bool $isUnique
     * @return bool
     * @throws InvalidArgumentException
     */
    private function checkIndexExists($table, $columns, $isUnique)
    {
        if (!is_array($columns) || count($columns) <= 0)
            throw new InvalidArgumentException('Incorrect call to checkIndexExists', 'columns');

        // Use the information schema to see if the index exists or not.
        // all users have permission to the information schema
        $sql = '
          SELECT * 
            FROM INFORMATION_SCHEMA.STATISTICS 
           WHERE table_schema=DATABASE() 
            AND table_name = \'' . $table . '\' 
            AND non_unique = \'' . (($isUnique) ? 0 : 1) . '\'
            AND (
        ';

        $i = 0;
        foreach ($columns as $column) {
            $i++;
            $sql .= (($i == 1) ? '' : ' OR') . ' (seq_in_index = \'' . $i . '\' AND column_name = \'' . $column . '\') ';
        }
        $sql .= ' )';
        $indexes = $this->fetchAll($sql);
        
        return (count($indexes) === count($columns));
    }

}
