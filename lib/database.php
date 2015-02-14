<?php
/* Don't allow direct access */
//defined('START') or die();

require_once('config.php');

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
//
//function add_entry($connection, $author, $title, $url, $description) {
//    $sql = "INSERT INTO video(author, title, parts, description)"
//            . "VALUES('{$author}', '{$title}', NULL, '{$description}')";
//    $result = mysqli_query($connection, $sql);
//    if(!$result) {
//        // ToDo: output message probably through SESSION
//        echo "Could not write to video table";
//    }
//
//    $video_id = get_video_id($title);
//
//    add_part($connection,$title,$url,"NULL");
//}

function add_creditee($connection) {
    $temp_id = mt_rand(-2^32/2,2^32/2-1);

    $sql = "INSERT INTO creditee(temp_id)"
        . "VALUES('{$temp_id}')";
    $result = mysqli_query($connection, $sql);
    if(!$result) {
        // ToDo: output message probably through SESSION
        echo "Could not enter data";
        return;
    }

    $sql = "SELECT id FROM creditee WHERE temp_id LIKE '{$temp_id}'";
    $result = mysqli_query($connection, $sql);
    if(!$result) {
        // ToDo: output message probably through SESSION
        echo "Could not enter data";
        return;
    }

    $creditee_id = mysqli_fetch_array($result)[0];

    $sql = "UPDATE creditee SET temp_id = NULL WHERE id LIKE '{$creditee_id}'";
    $result = mysqli_query($connection, $sql);
    if(!$result) {
        // ToDo: output message probably through SESSION
        echo "Could not update table";
        return;
    }

    return $creditee_id;
}

function add_creditee_attribute($connection, $creditee_id, $attribute_name, $attribute_value) {
    $sql = "SELECT id FROM creditee_attribute_type WHERE name LIKE '{$attribute_name}'";
    $result = mysqli_query($connection, $sql);
    if(!$result) {
        // ToDo: output message probably through SESSION
        echo "Could not retrieve data";
        return;
    }

    $creditee_attribute_type_id = mysqli_fetch_array($result)[0];

    $sql = "INSERT INTO creditee_attribute(creditee_id, creditee_attribute_type_id, value)"
        . "VALUES('{$creditee_id}', '{$creditee_attribute_type_id}', '{$value}')";
    $result = mysqli_query($connection, $sql);
    if(!$result) {
        // ToDo: output message probably through SESSION
        echo "Could not enter data";
    }
}