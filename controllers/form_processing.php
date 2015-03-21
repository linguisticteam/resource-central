<?php
/* Don't allow direct access */
//defined('START') or die();
require_once(dirname(dirname(__FILE__)) . '/models/database.php');
require_once('error.php');
require_once('form_processor.php');

//$connection = db_connect();

//ToDo: check whether these are empty one by one and output appropriate error message if so
if(!empty($_POST['title']) && !empty($_POST['resource_type']) && !empty($_POST['url']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {
 
   $authors = $FormProcessor->GetAuthors();
    
    //ToDo: Check for raised errors, cancel operation if found
    
    add_entry($connection,
            $FormProcessor->escapeString($_POST['title']),
            $FormProcessor->escapeString($_POST['resource_type']),
            $FormProcessor->escapeString($_POST['url']),
            $authors,
            $FormProcessor->escapeString($_POST['keywords']),
            $FormProcessor->escapeString($_POST['description']));
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
