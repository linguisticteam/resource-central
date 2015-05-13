<?php
/* Don't allow direct access */
//defined('START') or die();

require_once(dirname(dirname(__FILE__)) . '/admin/config.php');
//require_once(dirname(dirname(__FILE__)) . '/controllers/error.php');

class Database extends mysqli {
    //Dependencies
    private $Error;

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
    
    public function GetResources($limit_offset, $limit_maxNumRows) {
        $sql = "SELECT resource.id AS resource_id, title, (
                SELECT resource_type.name
                FROM resource_type
                WHERE resource_type.id = resource.resource_type_id
                ) AS resource_type, description
                FROM resource
                LIMIT " . (int) $limit_offset . ", " . (int) $limit_maxNumRows;
        
        $result = $this->query($sql);
        
        if(!$result) {
            $this->Error->raise(__FILE__, __LINE__, 'GetResourcesMethodFailed');
            return false;
        }
        
        return $result;
    }
    
    public function GetKeywords($resource_id) {
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
    
    //This method is to be expanded in the future, to include more than the URL
    public function GetResourceURLs($resource_id) {
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
    
    public function GetAuthors($resource_id) {
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
}
