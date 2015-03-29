<?php
/* Don't allow direct access */
//defined('START') or die();
require_once(dirname(dirname(__FILE__)) . '/models/database.php');
require_once('error.php');
require_once('form_processor.php');
 

    //Get and validate all user-suppled values from the form
   $title = $FormProcessor->GetValidatedTitle();
   $resource_type = $FormProcessor->GetValidatedResourceType();
   $url = $FormProcessor->GetValidatedUrl();
   $authors = $FormProcessor->GetValidatedAuthors();
   $keywords = $FormProcessor->GetValidatedKeywords();
   $description = $FormProcessor->GetValidatedDescription();
    
    //Check for raised errors, cancel operation if found
   if(Error::count() > 0) {
       Error::print_all();
       exit;
   }
    
   //Put the values into the AddingEntry object
    $AddingEntry->SetProperties(
            $title,
            $resource_type,
            $url,
            $authors,
            $keywords,
            $description);
    
    //Insert the values to the database
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
