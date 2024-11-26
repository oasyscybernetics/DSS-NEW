<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class NullableTextFieldsMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        $notNullableTextColumnsQuery = $this->query('SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE DATA_TYPE = \'text\' AND IS_NULLABLE = \'NO\' AND TABLE_SCHEMA = DATABASE() ' );
        $notNullableTextColumns = $notNullableTextColumnsQuery->fetchAll(PDO::FETCH_ASSOC);

        foreach ($notNullableTextColumns as $columns) {
            $this->execute('ALTER TABLE ' . $columns['TABLE_NAME'] . ' MODIFY ' . $columns['COLUMN_NAME'] . ' TEXT NULL;');
        }
    }
}
