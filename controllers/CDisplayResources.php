<?php
defined('START') or die();

class CDisplayResources {
    //Dependencies
    private $MDatabase;
    
    public function __construct(MDatabase $MDatabase) {
        $this->MDatabase = $MDatabase;
    }

    public function ReturnResourceIDsForSearch($search_query) {

        //Grab resource IDs for that query
        $result = $this->MDatabase->GetResourceIDsForSearchQuery($search_query);

        //If there are no results for this search query, return false
        if(!$result) {
            return false;
        }

        //If there are results for this search query, carry on
        $resource_IDs = '';
        $total_rows = $result->num_rows;

        for($i = 0; $i < $total_rows; $i++) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $resource_IDs .= $row[0] . ',';
        }

        //Trim the last comma since we don't need it
        $resource_IDs = rtrim($resource_IDs, ',');

        //Return a comma-separated string of the resource IDs 
        return $resource_IDs;  
    }

}