<?php
require_once(dirname(__FILE__) . '/helpers/class_loader.php');

//Specify the encoding
header('Content-Type: text/html; charset=utf-8');

//If there is a search query specified, assign it to $search_query
if(!empty($_GET['q'])) {
    $search_query = $_GET['q'];
}
?>

<html>
    <head>
        <title>Resource Central</title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    
    <body>
    <h1>Welcome to the Resource Central</h1>
    <p>This site has a collection of useful resources that we have gathered over the years</p>
    
    <div id="right_hand_side">
    
    <div id="search">
            <form action="controllers/search_form_processing.php" method="post" name="search" accept-charset="utf-8">
                <input type="text" size="50" name="search_query" placeholder="Search...">
                <input type="submit" value="Search">
            </form>
    </div>
    
    <div id="resources_display">
        
        <?php $ViewDisplayResources->DisplayResources($search_query); ?>
        
        <div class="pagination">
            <?php $VPagination->DisplayPagination(); ?>
        </div>
        <div class="clear"></div>
    </div>
    </div>
    <br>
    <div id="footer">
        <span><a href="//github.com/linguisticteam/resource-central/" target="_blank">View this project on github</a></span>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <span>Contact us</span>
    </div>
    </body>
</html>