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

function get_video_id($title) {
    $sql = "SELECT id"
            . " FROM video"
            . " WHERE title = '" . $title . "'";
    $connection = db_connect();
    $result = mysqli_query($connection, $sql);
    if(!$result) {
        // ToDo: output message probably through SESSION
        echo "Could not read from video table";
    }
    $row = mysqli_fetch_array($result);
    return $row[0];
}

function add_entry($connection, $author, $title, $url, $description) {
    $sql = "INSERT INTO video(author, title, parts, description)"
            . "VALUES('{$author}', '{$title}', NULL, '{$description}')";
    $result = mysqli_query($connection, $sql);
    if(!$result) {
        // ToDo: output message probably through SESSION
        echo "Could not write to video table";
    }

    $video_id = get_video_id($title);

    add_part($connection,$title,$url,"NULL");
}
