<?php
/* Don't allow direct access */
//defined('START') or die();

require_once('database.php');

if(!empty($_POST['title']) && !empty($_POST['resource_type']) && !empty($_POST['url']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {
    $connection = db_connect();
    add_entry($connection, mysqli_real_escape_string($connection, $_POST['title']),
            mysqli_real_escape_string($connection, $_POST['resource_type']),
            mysqli_real_escape_string($connection, $_POST['url']),
            mysqli_real_escape_string($connection, $_POST['keywords']),
            mysqli_real_escape_string($connection, $_POST['description']));
}


for($i = 2; true; $i++) {
    if(!empty($_POST['part_' . $i . '_title']) && !empty($_POST['part_' . $i . '_url'])) {
        add_part(mysqli_real_escape_string($connection, $_POST['part_' . $i . '_title']),
                mysqli_real_escape_string($connection, $_POST['part_' . $i . '_url']));
    }
    else {
        break;
    }
}