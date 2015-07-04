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
            
            //Get resource IDs that match the search query
            $resource_IDs = $CDisplayResources->ReturnResourceIDsForSearch();
        
            //If there are matching resource IDs, display those resources
            if($resource_IDs) {
                $ViewDisplayResources->DisplayResources($resource_IDs);
            }
            //Else if nothing is returned, say so
            else {
                 echo 'No results.';
            }
        }
        
        //If there is no search query specified, just display all the resources
        else {
            $ViewDisplayResources->DisplayResources($resource_IDs); 
        }
        ?>
        
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