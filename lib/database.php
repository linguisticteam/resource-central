<?php
/* Don't allow direct access */
defined('START') or die();

function db_connect() {
    $connection = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    return $connection;
}

function add_entry() {
    
}