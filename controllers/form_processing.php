<?php
/* Don't allow direct access */
//defined('START') or die();
require_once(dirname(dirname(__FILE__)) . '/models/database.php');
require_once('error.php');
require_once('form_processor.php');
 

    //Get and validate all user-suppled values from the form
   $title = $FormProcessor->GetTitle();
   $resource_type = $FormProcessor->GetValidatedResourceType();
   $url = $FormProcessor->GetValidatedUrl();
   $authors = $FormProcessor->GetValidatedAuthors();
   $keywords = $FormProcessor->GetValidatedKeywords();
    
    //Check for raised errors, cancel operation if found
   if(Error::count() > 0) {
       Error::print_all();
       exit;
   }
    
    $AddingEntry->SetProperties(
            $title,
            $resource_type,
            $url,
            $authors,
            $keywords,
            $FormProcessor->escapeString($_POST['description']));
    
    $AddingEntry->InsertToDb();


for($i = 2; true; $i++) {
    if(!empty($_POST['part_' . $i . '_title']) && !empty($_POST['part_' . $i . '_url'])) {
        add_part(mysqli_real_escape_string($connection, $_POST['part_' . $i . '_title']),
                mysqli_real_escape_string($connection, $_POST['part_' . $i . '_url']));
    }
    else {
        break;
    }
}
