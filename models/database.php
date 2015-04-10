<?php
/* Don't allow direct access */
//defined('START') or die();

require_once(dirname(dirname(__FILE__)) . '/admin/config.php');
//require_once(dirname(dirname(__FILE__)) . '/controllers/error.php');

class Database extends mysqli {

    public function __construct() {
        parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    
    public function GetTypes($table_name, $column_name) {
        $types_array = array();
        
        $sql = "SELECT {$column_name} FROM {$table_name}";
        $result = $this->query($sql);
        
        if(!$result) {
            Error::raise(__FILE__, __LINE__, 'GetTypesMethodFailed');
            return;
        }
        
        $total_rows = $result->num_rows;
        
        for($i = 0; $i < $total_rows; $i++) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $types_array[] = $row[0];
        }
        
        return $types_array;
    }
    
    public function GetResources() {
        $sql = "SELECT resource.id AS resource_id, title, (
                SELECT resource_type.name
                FROM resource_type
                WHERE resource_type.id = resource.resource_type_id
                ) AS resource_type, description
                FROM resource";
        
        $result = $this->query($sql);
        
        if(!$result) {
            Error::raise(__FILE__, __LINE__, 'GetResourcesMethodFailed');
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
            Error::raise(__FILE__, __LINE__, 'GetKeywordsMethodFailed');
            return false;
        }
        
        return $result;
    }
}

//$Database = new Database;