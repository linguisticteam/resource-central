<?php
/* Don't allow direct access */
//defined('START') or die();
require_once('database.php');

$connection = db_connect();

class FieldType {
    const RESOURCE_AUTHOR = 0;
    const AUTHOR_TYPE = 1;
}

class FormProcessor {
    private $author_type_array = [];
    private $resource_author_array = [];

    public function __construct () { 
    }

    public function addResourceAuthorToArray ($Index) {
        $tempResourceAuthor = FormProcessor::getEscapedField('resource_author_' . $Index);

        if (FormProcessor::containsComma($tempResourceAuthor) == true) {
            // Error
            echo("Error: Resource Author Name contains reserved character: ','\n");
            return false;
        }

        // Ok
        $resource_author_array[] = $tempResourceAuthor;
        return true;
    }

    public function addAuthorTypeToArray ($Index) {
        $author_type_array[] = FormProcessor::getEscapedField('author_' . $Index . '_type');
    }

    /* Static Functions */

    public static function isFieldPresent ($Field) {
        return !empty($_POST[$Field]);
    }

    public static function getField ($Field) {
        return $_POST[$Field];
    }

    public static function getEscapedField ($Field) {
        $tempField = FormProcessor::getField($Field);
        return FormProcessor::escapeString($tempField);
    }

    public static function escapeString ($String) {
        global $connection;
        return mysqli_real_escape_string($connection,$String);
    }

    public static function containsComma ($String) {
        // Check if $String contains commas
        $Result = strstr($String,',');

        // If it does not, then return false
        if ($Result == false) {
            return false;
        }

        // If it does, then return true
        return true;
    }
}

if(!empty($_POST['title']) && !empty($_POST['resource_type']) && !empty($_POST['url']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {

    /* Declare variables */

    $FormProcInstance = new FormProcessor;

    for($i = 0; true; $i++) {
        $resource_author_exists = FormProcessor::isFieldPresent('resource_author_' . $i);
        $author_type_exists = FormProcessor::isFieldPresent('author_' . $i . '_type');

        if( $resource_author_exists &&
            $author_type_exists) {
            // Both author name and author type exist.
            $FormProcInstance->addResourceAuthorToArray($i);
            $FormProcInstance->addAuthorTypeToArray($i);
        }
        elseif(!$resource_author_exists &&
               !$author_type_exists) {
            // Neither author name nor author type exist.
            break;
        }
        elseif( $resource_author_exists &&
               !$author_type_exists) {
            // Only author name exists.
            echo "Please select an Author Type\n";
        }
        elseif(!$resource_author_exists &&
                $author_type_exists) {
            // Only author type exists.
            echo "Please specify a Resource Author\n";
        }
    }
    
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
