<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class AddForeignKeysToWidgetMediaMigration
 */
class AddForeignKeysToWidgetMediaMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        if (!$this->fetchRow('
            SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS WHERE constraint_schema=DATABASE()
                AND `table_name` = \'lkwidgetmedia\' AND referenced_table_name = \'media\';')) {

            $this->execute('DELETE FROM `lkwidgetmedia` WHERE NOT EXISTS (SELECT mediaId FROM `media` WHERE `media`.mediaId = `lkwidgetmedia`.mediaId) ');

            // Add the constraint
            $this->execute('ALTER TABLE `lkwidgetmedia` ADD CONSTRAINT `lkwidgetmedia_ibfk_1` FOREIGN KEY (`mediaId`) REFERENCES `media` (`mediaId`);');
        }

        if (!$this->fetchRow('
            SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS WHERE constraint_schema=DATABASE()
                AND `table_name` = \'lkwidgetmedia\' AND referenced_table_name = \'widget\';')) {

            $this->execute('DELETE FROM `lkwidgetmedia` WHERE NOT EXISTS (SELECT widgetId FROM `widget` WHERE `widget`.widgetId = `lkwidgetmedia`.widgetId) ');

            // Add the constraint
            $this->execute('ALTER TABLE `lkwidgetmedia` ADD CONSTRAINT `lkwidgetmedia_ibfk_2` FOREIGN KEY (`widgetId`) REFERENCES `widget` (`widgetId`);');
        }
    }
}
