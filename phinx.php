<?php
/**
 * Oasys - Digital Signage - http://oasys.co
 
 *
 */

// We need to define XIBO as the settings.php file is protected with a !defined check.
if (!defined('XIBO')) {
    DEFINE('XIBO', true);
}

// Return the PHINX configuration object
// this should be based on our settings.php file
// in the case of a Docker installation the settings.php file will contain $_SERVER vars which we can use directly
// as they are environment variables in the container (and the phinx phar file will have them)
// in the case of a manual installation, these will be hard-coded in the settings file - in which case we can still
// use them.
require('web/settings.php');

// Our settings file exposes HOST:PORT rather that separate variables
if (strstr($dbhost, ':')) {
    $hostParts = explode(':', $dbhost);
    $dbhost = $hostParts[0];
    $dbport = $hostParts[1];
} else {
    $dbport = 3306;
}

// Phinx formatted config array using the settings we've harvested from our settings.php file
return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations'
    ],
    'environments' => [
        'default_database' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'charset' => 'utf8',
            'host' => $dbhost,
            'port' => $dbport,
            'name' => $dbname,
            'user' => $dbuser,
            'pass' => $dbpass,
        ]
    ]
];