<?php
/* Don't allow direct access */
//defined('START') or die();

require_once(dirname(dirname(__FILE__)) . '/admin/config.php');
//require_once(dirname(dirname(__FILE__)) . '/controllers/error.php');

class MDatabase extends mysqli {
    //Dependencies
    private $Error;
   
    public $num_resources_found;

    public function __construct(Error $Error) {
        //Dependencies
        $this->Error = $Error;
        
        //Establish database connection
        parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //Set the connection encoding
        if(!$this->set_charset("utf8")) {
            echo 'not charse';
        }
    }
    
    public function GetTypes($table_name, $column_name) {
        $types_array = array();
        
        $sql = "SELECT {$column_name} FROM {$table_name}";
        $result = $this->query($sql);
        
        if(!$result) {
            $this->Error->raise(__FILE__, __LINE__, 'GetTypesMethodFailed');
            return;
        }
        
        $total_rows = $result->num_rows;
        
        for($i = 0; $i < $total_rows; $i++) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $types_array[] = $row[0];
        }
        
        return $types_array;
    }
    
    //Get all or only specified resources. Limit the number of results for pagination purposes.
    public function GetResources($limit_offset, $limit_maxNumRows, $resource_IDs = NULL) {
        $sql = "SELECT resource.id AS resource_id, title, (
                SELECT resource_type.name
                FROM resource_type
                WHERE resource_type.id = resource.resource_type_id
                ) AS resource_type, publishing_date, description
                FROM resource";
        
        //If there are specified resource_IDs, get only them
        if($resource_IDs !== NULL) {
                $sql .= " WHERE resource.id IN (" . $resource_IDs . ")";
        }
        
        $sql .= " LIMIT " . (int) $limit_offset . ", " . (int) $limit_maxNumRows;
        
        $result = $this->query($sql);
        
        if(!$result) {
            $this->Error->raise(__FILE__, __LINE__, 'GetResourcesMethodFailed');
            return false;
        }
        
        return $result;
    }
    
    ////Get the keywords for a specified resource_id
    public function GetKeywordsForResource($resource_id) {
        $sql = "SELECT (
                SELECT name
                FROM keyword
                WHERE keyword.id = keyword_xref.keyword_id
                ) AS keyword
                FROM keyword_xref
                WHERE resource_id =" . (int) $resource_id;
        
        $result = $this->query($sql);
        
        if(!$result) {
            $this->Error->raise(__FILE__, __LINE__, 'GetKeywordsMethodFailed');
            return false;
        }
        
        return $result;
    }
    
    ////Get the URLs for a specified resource_id
    //This method is to be expanded in the future, to include more than the URL
    public function GetURLsForResource($resource_id) {
        $sql = "SELECT url
                FROM resource
                WHERE id = " . (int) $resource_id;
        
        $result = $this->query($sql);
        
        if(!$result) {
            $this->Error->raise(__FILE__, __LINE__, 'GetResourceURLMethodFailed');
            return false;
        }
        
        return $result;
    }
    
    //Get the authors for a specified resource_id
    public function GetAuthorsForResource($resource_id) {
        $sql = "SELECT full_name, (
                SELECT name
                FROM author_type AS at
                WHERE at.id = a.author_type_id
                ) AS author_type
                FROM author AS a
                WHERE a.resource_id = " . (int) $resource_id;
        
        $result = $this->query($sql);
        
        if(!$result) {
            $this->Error->raise(__FILE__, __LINE__, 'GetAuthorsMethodFailed');
            return false;
        }
        
        return $result;
    }
    
    public function GetTotalNumResources() {
        $sql = "SELECT COUNT(*) AS total_num FROM resource";
        $result = $this->query($sql);
        
        if(!$result) {
            $this->Error->raise(__FILE__, __LINE__, 'GetTotalNumResourcesMethodFailed');
            return false;
        }
        
        $total_rows = $result->num_rows;
        
        for($i = 0; $i < $total_rows; $i++) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $total_num_resources = $row['total_num'];
        }
        
        return (int) $total_num_resources;
    }
    
    //Get the number of resources that match a search query
    public function GetTotalNumResourcesForSearchQuery($search_query) {
       
        //Check if the number of resources for the search query has already been found 
        if(!$this->num_resources_found) {
            
            //If not, call this method which sets it
            $this->GetResourceIDsForSearchQuery($search_query);
        }
        
        return $this->num_resources_found;
    }
    
    //Get the resource IDs that match a search query
    public function GetResourceIDsForSearchQuery($search_query) {
        $sql = "SELECT * FROM `resource` WHERE `id` IN ("
                . "SELECT `resource_id` FROM `keyword_xref` WHERE `keyword_id` IN ("
                . "SELECT `id` FROM `keyword` WHERE `name` LIKE '%" . $search_query . "%'))";
        
        $result = $this->query($sql);
        
        //This method can genuinely return 0 rows so we check against 'false'
        if($result === false) {
            $this->Error->raise(__FILE__, __LINE__, 'GetResourceIDsForSearchMethodFailed');
            return false;
        }
        
        //Set the number of resources found for the search query
        $this->num_resources_found = $result->num_rows;
        
        return $result;
    }
    
    /* Get the keywords and order them by those that are most commonly found */
    public function GetKeywordsByPopularity() {
        $sql = "SELECT ("
                . "SELECT `name` FROM `keyword` WHERE keyword.id = keyword_xref.keyword_id"
                . ") as keyword"
                . " FROM `keyword_xref` "
                . "GROUP BY `keyword_id` "
                . "ORDER BY COUNT(keyword_id) DESC";
        
        $result = $this->query($sql);
        
        if(!$result) {
            $this->Error->raise(__FILE__, __LINE__, 'GetKeywordsByPopularityMethodFailed');
            return false;
        }
        
        return $result;
    }
}
