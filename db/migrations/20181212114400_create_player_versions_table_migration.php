<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

/**
 * Class CreatePlayerVersionsTableMigration
 * Create a new table to store information about player versions
 * Install playersoftware widget
 * Remove apk,ipk from validExtensions in genericfiles module
 */
class CreatePlayerVersionsTableMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        if (!$this->hasTable('player_software')) {
            $versions = $this->table('player_software', ['id' => 'versionId']);

            $versions->addColumn('player_type', 'string', ['limit' => 20, 'default' => null, 'null' => true])
                ->addColumn('player_version', 'string', ['limit' => 15, 'default' => null, 'null' => true])
                ->addColumn('player_code', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_SMALL, 'null' => true])
                ->addColumn('mediaId', 'integer')
                ->addForeignKey('mediaId', 'media', 'mediaId')
                ->create();
        }

        // Add the player_software module
        $modules = $this->table('module');
        if (!$this->fetchRow('SELECT * FROM module WHERE module = \'playersoftware\'')) {
            $modules->insert([
                'module' => 'playersoftware',
                'name' => 'Player Software',
                'enabled' => 1,
                'regionSpecific' => 0,
                'description' => 'A module for managing Player Versions',
                'schemaVersion' => 1,
                'validExtensions' => 'apk,ipk,wgt',
                'previewEnabled' => 0,
                'assignable' => 0,
                'render_as' => null,
                'viewPath' => '../modules',
                'class' => 'Xibo\Widget\PlayerSoftware',
                'defaultDuration' => 10
            ])->save();
        }

        // remove apk and ipk from valid extensions in generic file module
        $this->execute('UPDATE `module` SET validextensions = REPLACE(validextensions, \'apk,ipk\', \'\') WHERE module = \'genericfile\' LIMIT 1;');
    }
}
