<?php
/* Don't allow direct access */
//defined('START') or die();
require_once('database.php');
if(!empty($_POST['title']) && !empty($_POST['resource_type']) && !empty($_POST['url']) && !empty($_POST['keywords']) && !empty($_POST['description'])) {
    $resource_author_array = [];
    $author_type_array = [];
    for($i = 0; true; $i++) {
        $resource_author_exists = !empty($_POST['resource_author_' . $i]);
        $author_type_exists = !empty($_POST['author_' . $i . '_type']);
        
        if( $resource_author_exists &&
            $author_type_exists) {
            // Both author name and author type exist.
            $resource_author = mysqli_real_escape_string($_POST['resource_author_' . $i]);
            $author_type = mysqli_real_escape_string($_POST['author_' . $i . '_type']);
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
