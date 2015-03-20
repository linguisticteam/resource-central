<?php
/* Don't allow direct access */
//defined('START') or die();

require_once(dirname(dirname(__FILE__)) . '/lib/config.php');

function db_connect() {
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if(!$connection) {
        echo "Could not connect to database: " . mysqli_errno($connection);
    }
    return $connection;
}

//OBSOLETE
//
//function get_video_id($title) {
//    $sql = "SELECT id"
//            . " FROM video"
//            . " WHERE title = '" . $title . "'";
//    $connection = db_connect();
//    $result = mysqli_query($connection, $sql);
//    if(!$result) {
//        // ToDo: output message probably through SESSION
//        echo "Could not read from video table";
//    }
//    $row = mysqli_fetch_array($result);
//    return $row[0];
//}

function add_entry($connection, $title, $resource_type, $url, $authors, $keywords, $description) {
    //Return with error if resource title already exist
    $sql = "SELECT COUNT(title) FROM resource WHERE title LIKE '{$title}'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($result);
    if($row[0] > 0) {
        echo 'A resource with this title already exists';
        return;
    }
    
    //Add resource
    $sql = "CALL insert_resource ('{$title}', '{$resource_type}', '{$url}', '{$description}')";
    $result = mysqli_query($connection, $sql);
    if(($rows = mysqli_affected_rows($connection)) == 0) {
        // ToDo: output message probably through SESSION
        echo "insert_resource procedure failed";
        return;
    }
    
    //Add keywords
    $pieces = explode(",", $keywords);
    foreach($pieces as $piece) {
        $piece = trim($piece);
        //Add the keyword name if it's a new keyword
        $sql = "CALL insert_keyword ('{$piece}')";
        mysqli_query($connection, $sql);
        //Add the keyword-resource relations
        $sql = "CALL insert_keyword_xref ('{$title}', '{$piece}')";
        $result = mysqli_query($connection, $sql);
        if(!$result) {
            echo "Could not add keywords for the resource";
            return;
        }
    }
    
    //Add authors
    $sql ="CALL insert_authors('{$authors}', '{$title}')";
    $result = mysqli_query($connection, $sql);
    if(!$result) {
        echo "Could not add authors to the database";
        return;
    }
    
       echo "Resource added successfully";

    //$video_id = get_video_id($title);

    //add_part($connection,$title,$url,"NULL");
}

