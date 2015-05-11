<?php
    require_once(dirname(__FILE__) . '/helpers/class_loader.php');
?>

<html>
    <head>
        <title>Resource Central</title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    
    <body>
    <h1>Welcome to the Resource Central</h1>
    <p>This site has a collection of useful resources that we have gathered over the years</p>
    
    <div id="resources_display">
        <?php $ViewDisplayAllResources->DisplayAll(); ?>
        
        <div class="pagination">
            <?php $VPagination->DisplayPagination(); ?>
        </div>
        <div class="clear"></div>
    </div>
    <br>
    <div id="footer">
        <span><a href="//github.com/linguisticteam/resource-central/" target="_blank">View this project on github</a></span>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <span>Contact us</span>
    </div>
    </body>
</html>