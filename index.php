<?php
//Load all the classes
require_once(dirname(__FILE__) . '/helpers/class_loader.php');
//require_once(dirname(__FILE__) . '/controllers/display_resources.php');

//Display the header
$VHeader->DisplayHeader();
?>    
    <div id="right_hand_side">
    
    <div id="search">
            <form action="controllers/search_form_processing.php" method="post" name="search" accept-charset="utf-8">
                <input type="text" size="50" name="search_query" placeholder="Search...">
                <input type="submit" value="Search">
            </form>
    </div>
    
    <div id="resources_display">
        
        <?php 
        //If there is a search query specified
        if(!empty($_GET['q'])) {
            
            //Sanitize it and assign it to $search_query
            $search_query = $Database->real_escape_string($_GET['q']);
            
            //Get resource IDs that match the search query
            $resource_IDs = $CDisplayResources->ReturnResourceIDsForSearch($search_query);
        
            //Display the resources that have those IDs
            $VDisplayResources->DisplayResources($resource_IDs);
        }
        
        //If there is no search query specified, just display all the resources
        else {
            $VDisplayResources->DisplayResources($resource_IDs); 
        }
        ?>
        
        <div class="pagination">
            <?php 
            //Update the variables for the pagination
            $CPagination->__construct($Database, $search_query);
            
            //Display the pagination
            $VPagination->DisplayPagination(); 
            ?>
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