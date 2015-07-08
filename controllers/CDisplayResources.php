<?php
class CDisplayResources {
    //Dependencies
    private $Database;
    
    public function __construct(Database $Database) {
        $this->Database = $Database;
    }

    public function ReturnResourceIDsForSearch() {
        //If there is a search query specified, sanitize it and assign it to $search_query
        
            $search_query = $this->Database->real_escape_string($_GET['q']);

            //Grab resource IDs for that query
            $result = $this->Database->GetResourceIDsForSearchQuery($search_query);

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