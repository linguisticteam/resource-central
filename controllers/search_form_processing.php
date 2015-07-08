<?php
//Set protocol
$protocol = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';

//If there is something entered in the Search field, sanitize it, set GET and return to index page
if(!empty($_POST['search_query'])) {
    //ToDo: do sanitization - what kind of?
    $search_query = $_POST['search_query'];

    header('Location: ' . $protocol . $_SERVER['SERVER_NAME'] . '?q=' . $search_query);
}
//If there is nothing in the Search field, return back to index page
else {
    header('Location: ' . $protocol . $_SERVER['SERVER_NAME']);
}