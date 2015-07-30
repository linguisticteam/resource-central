<?php
//This is a valid entry point
define('START', true);

//Load all the classes
require_once(dirname(__FILE__) . '/helpers/class_loader.php');

//Load DICE and configure it to use only one instance of every object
$Dice = new \Dice\Dice;
$rule = new \Dice\Rule;
$rule->shared = true;
$Dice->addRule('*', $rule);

//Process the search form for any search query entered
$CSearchFormProcessor = $Dice->create('CSearchFormProcessor');
$CSearchFormProcessor->ProcessForm();

//Display the header
$VHeader = $Dice->create('VHeader');
$VHeader->DisplayHeader();
?>    
    <div id="left_hand_side">
        <?php 
        $VDisplayKeywords = $Dice->create('VDisplayKeywords');
        $VDisplayKeywords->DisplayKeywordsByPopularity(); ?>
    </div>
    
    <div id="right_hand_side">
    
    <div id="search">
            <form action="index.php" method="post" name="search" accept-charset="utf-8">
                <input type="text" size="50" name="search_query" placeholder="Search..." 
                    <?php echo !empty($_GET['q']) ? "value='" . htmlspecialchars($_GET['q']) . "'" : ""; ?>>
                <input type="submit" value="Search">
            </form>
    </div>
    
    <div id="resources_display">
        
        <?php 
        //Instantiate VDisplayResources
        $VDisplayResources = $Dice->create('VDisplayResources');
        
        //Instantiate MDatabase
        $MDatabase = $Dice->create('MDatabase');
        
        //If there is a search query specified
        if(!empty($_GET['q'])) {
            
            //Sanitize the query for MySQL use and assign it to $search_query
            $search_query = $MDatabase->real_escape_string($_GET['q']);
            
            //Get resource IDs that match the search query
            $CDisplayResources = $Dice->create('CDisplayResources');
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
            //Instantiate CPagination
            $CPagination = $Dice->create('CPagination', [$search_query]);
            
            //Update the variables for the pagination
            $CPagination->__construct($MDatabase, $search_query);
            
            //Display the pagination
            $VPagination = $Dice->create('VPagination');
            $VPagination->DisplayPagination(); 
            ?>
        </div>
        <div class="clear"></div>
    </div>
    </div>
    <div class="clear"></div>
    <br>
    <div id="footer">
        <span><a href="//github.com/linguisticteam/resource-central/" target="_blank">View this project on github</a></span>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <span>Contact us</span>
    </div>
    </body>
</html>