<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
use Phinx\Migration\AbstractMigration;

/**
 * Class SplitTickerModuleMigration
 */
class SplitTickerModuleMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // Add the new module.
        $this->execute('
            INSERT INTO `module` 
                (`Module`, `Name`, `Enabled`, `RegionSpecific`, `Description`, `ImageUri`, `SchemaVersion`, `ValidExtensions`, `PreviewEnabled`, `assignable`, `render_as`, `settings`, `viewPath`, `class`, `defaultDuration`) 
            VALUES
                (\'datasetticker\', \'DataSet Ticker\', 1, 1, \'Ticker with a DataSet providing the items\', \'forms/ticker.gif\', 1, NULL, 1, 1, \'html\', NULL, \'../modules\', \'Xibo\\\\Widget\\\\DataSetTicker\', 10);        
        ');

        // Find all of the existing tickers which have a dataSet source, and update them to point at the new
        // module `datasetticker`
        $this->execute('
            UPDATE `widget` SET type = \'datasetticker\' WHERE type = \'ticker\' AND widgetId IN (SELECT DISTINCT widgetId FROM `widgetoption` WHERE `option` = \'sourceId\' AND `value` = \'2\')
        ');
    }
}
