<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

use Phinx\Migration\AbstractMigration;

class DisplayProfileCommandLinkFixMigration extends AbstractMigration
{
    /** @inheritdoc */
    public function change()
    {
        // query the database and look for duplicate entries
        $duplicatesData = $this->query('SELECT commandId, displayProfileId FROM lkcommanddisplayprofile WHERE commandId IN ( SELECT commandId FROM lkcommanddisplayprofile GROUP BY commandId HAVING COUNT(*) > 1) ');
        $rowsDuplicatesData = $duplicatesData->fetchAll(PDO::FETCH_ASSOC);
        
        // only execute this code if any duplicates were found
        if (count($rowsDuplicatesData) > 0) {
            $duplicates = [];
            // create new array with displayProfileId as the key
            foreach ($rowsDuplicatesData as $row) {
                $duplicates[$row['displayProfileId']][] = $row['commandId'];
            }

            // iterate through the arrays get unique commandIds, calculate the limit and execute Delete query.
            foreach ($duplicates as $displayProfileId => $commandId) {

                // commandId is an array, get the unique Ids from it
                $uniqueCommandIds = array_unique($commandId);

                // iterate through our array of uniqueCommandIds and calculate the LIMIT for our SQL Delete statement
                foreach ($uniqueCommandIds as $uniqueCommandId) {
                    // create an array with commandId as the key and count of duplicate as value
                    $limits = array_count_values($commandId);

                    // Limits is an array with uniqueCommandId as the key and count of duplicate as value, we want to leave one record, hence we subtract 1
                    $limit = $limits[$uniqueCommandId] - 1;

                    // if we have any duplicates then run the delete statement, for each displayProfileId with uniqueCommandId and calculated limit per uniqueCommandId
                    if ($limit > 0) {
                        $this->execute('DELETE FROM lkcommanddisplayprofile WHERE commandId = ' . $uniqueCommandId . ' AND displayProfileId = ' . $displayProfileId . ' LIMIT ' . $limit);
                    }
                }
            }
        }

        // add the primary key for CMS upgrades, fresh CMS Installations will have it correctly added in installation migration.
        if (!$this->fetchRow('SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_NAME = \'lkcommanddisplayprofile\' AND CONSTRAINT_TYPE = \'PRIMARY KEY\' AND CONSTRAINT_SCHEMA = Database();')) {
            $this->execute('ALTER TABLE lkcommanddisplayprofile ADD PRIMARY KEY (commandId, displayProfileId);');
        }
    }
}
