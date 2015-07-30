<?php
/* This is a valid entry point (at least for now) */
define('START', true);

//Load all classes
require_once(dirname(dirname(dirname(__FILE__))) . '/helpers/class_loader.php');
 
//Load DICE and configure it to use only one instance of every object
$Dice = new \Dice\Dice;
$rule = new \Dice\Rule;
$rule->shared = true;
$Dice->addRule('*', $rule);

//Instantiate FormProcessor
$FormProcessor = $Dice->create('FormProcessor');

//Get and validate all user-suppled values from the form
$title = $FormProcessor->GetValidatedTitle();
$resource_type = $FormProcessor->GetValidatedResourceType();
$url = $FormProcessor->GetValidatedUrl();
$authors = $FormProcessor->GetValidatedAuthors();
$keywords = $FormProcessor->GetValidatedKeywords();
$publishing_date = $FormProcessor->GetValidatedPublishingDate();
$description = $FormProcessor->GetValidatedDescription();

//Instantiate Error
$Error = $Dice->create('Error');

//Check for raised errors, cancel operation if found
if($Error->count() > 0) {
   $Error->print_all();
   exit;
}

//Instantiate MAddingEntry
$MAddingEntry = $Dice->create('MAddingEntry');

//Put the values into the MAddingEntry object
$MAddingEntry->SetProperties(
        $title,
        $resource_type,
        $url,
        $authors,
        $keywords,
        $publishing_date,
        $description);

//Insert the values to the database
$MAddingEntry->InsertToDb();

//Check for raised errors
    if($Error->count() > 0) {
        $Error->print_all();
        exit;
    }

    //If there are no raised errors, resource is added successfully
    echo "Resource added successfully";


        /* old code
  for($i = 2; true; $i++) {

    if(!empty($_POST['part_' . $i . '_title']) && !empty($_POST['part_' . $i . '_url'])) {
        add_part(mysqli_real_escape_string($connection, $_POST['part_' . $i . '_title']),
                mysqli_real_escape_string($connection, $_POST['part_' . $i . '_url']));
    }
    else {
        break;
    }
}
*/