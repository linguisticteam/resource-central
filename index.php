<?php
define('START', true);

require_once('lib/config.php');
require_once('lib/database.php');

$connection = db_connect();
if(!$connection) {
    echo "Could not connect to database: " . mysql_error();
}
?>
<div><a href="add_resource.php">Add a new resource</a></div>
<div><a href="add_resource_element.php">Add an element for an existing resource</a></div>
<div><a href="add_entity.php">Add a new resource, resource element or entity type</a></div>