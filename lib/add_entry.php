<?php
/* Don't allow direct access */
//defined('START') or die();
require_once('database.php');

$connection = db_connect();

class FormProcessor {
    private $author_type = null;
    private $author_type_array = [];
    private $resource_author = null;
    private $resource_author_array = [];

    public function __construct () { 
    }

    public static function isFieldPresent ($Field) {
        return !empty($_POST[$Field]);
    }

    public static function getField ($Field) {
        return $_POST[$Field];
    }

    public static function getEscapedField ($Field) {
        $tempField = this::getField($Field);
        return this::escapeString($tempField);
    }

    public static function escapeString ($String) {
        global $connection;
        return mysqli_real_escape_string($connection,$String);
    }
}

if(!empty($_POST['title']) && !empty($_POST['resource_type']) && !empty($_POST['url']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {

    /* Declare variables */

    for($i = 0; true; $i++) {
        $resource_author_exists = FormProcessor::isFieldPresent('resource_author_' . $i);
        $author_type_exists = FormProcessor::isFieldPresent('author_' . $i . '_type');

        if( $resource_author_exists &&
            $author_type_exists) {
            // Both author name and author type exist.
            $resource_author = FormProcessor::getEscapedField('resource_author_' . $i);
            $author_type = FormProcessor::getEscapedField('author_' . $i . '_type');
            $resource_author_array[] = $resource_author;
            $author_type_array[] = $author_type;
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
