<?php
/* Don't allow direct access */
//defined('START') or die();
require_once(dirname(dirname(__FILE__)) . '/models/database.php');
require_once('error.php');
require_once('form_processor.php');

//ToDo: check whether these are empty one by one and output appropriate error message if so
if(!empty($_POST['title']) && !empty($_POST['resource_type']) && !empty($_POST['url']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {
 
   $title = $FormProcessor->GetTitle();
   $authors = $FormProcessor->GetAuthors();
   $keywords = $FormProcessor->GetValidatedKeywords();
    
    //ToDo: Check for raised errors, cancel operation if found
    
    $AddingEntry->SetProperties(
            $title,
            $FormProcessor->escapeString($_POST['resource_type']),
            $FormProcessor->escapeString($_POST['url']),
            $authors,
            $keywords,
            $FormProcessor->escapeString($_POST['description']));
    
    $AddingEntry->InsertToDb();
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
