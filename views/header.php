<?php
defined('START') or die();

class VHeader {
    
    public function DisplayHeader() {
        //Specify the encoding
        header('Content-Type: text/html; charset=utf-8');
?>

<html>
    <head>
        <title>Resource Central</title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    
    <body>
    <h1>Welcome to the Resource Central</h1>
    <p>This site has a searchable collection of useful resources</p>
<?php
    }
}