<?php
/* Don't allow direct access */
//defined('START') or die();
require_once(dirname(dirname(__FILE__)) . '/models/database.php');
require_once('error.php');
require_once('form_processor.php');

$connection = db_connect();


if(!empty($_POST['title']) && !empty($_POST['resource_type']) && !empty($_POST['url']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {
 
    //ToDo: put the for loop in the FormProcessor class
    for($i = 0; true; $i++) {
        $resource_author_exists = FormProcessor::isFieldPresent('resource_author_' . $i);
        $author_type_exists = FormProcessor::isFieldPresent('author_' . $i . '_type');

        if( $resource_author_exists &&
            $author_type_exists) {
            // Both author name and author type exist.
            $FormProcessor->addFieldToArray(0, $i);
            $FormProcessor->addFieldToArray(1, $i);
        }
        elseif(!$resource_author_exists &&
               !$author_type_exists) {
            // Neither author name nor author type exist.
            break;
        }
        elseif( $resource_author_exists &&
               !$author_type_exists) {
            // Only author name exists.
            $Error->raise('SelectAuthorType');
        }
        elseif(!$resource_author_exists &&
                $author_type_exists) {
            // Only author type exists.
            $Error->raise('SpecifyResourceAuthor');
        }
    }
    
    //ToDo: Check for raised errors, cancel operation if found
    
    add_entry($connection,
            $FormProcessor->escapeString($_POST['title']),
            $FormProcessor->escapeString($_POST['resource_type']),
            $FormProcessor->escapeString($_POST['url']),
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
