<?php
define('START', true);

require_once('lib/config.php');
require_once('/lib/database.php');

$connection = db_connect();
if(!$connection) {
    echo "Could not connect to database: " . mysql_error();
}
