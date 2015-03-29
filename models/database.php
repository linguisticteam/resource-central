<?php
/* Don't allow direct access */
//defined('START') or die();

require_once(dirname(dirname(__FILE__)) . '/lib/config.php');
require_once(dirname(dirname(__FILE__)) . '/controllers/error.php');

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
}

$Database = new Database;

class AddingEntry extends Database {
    private $title;
    private $resource_type;
    private $url;
    private $authors;
    private $keywords;
    private $description;
    
    public function SetProperties($title, $resource_type, $url, $authors, $keywords, $description) {
        //Call the setter methods one by one
        $this->SetTitle($title);
        $this->SetResourceType($resource_type);
        $this->SetURL($url);
        $this->SetAuthors($authors);
        $this->SetKeywords($keywords);
        $this->SetDescription($description);
    }

    public function InsertToDb() {

        $this->AddResource();
        $this->AddKeywords();
        $this->AddAuthors();

        //Check for raised errors, cancel operation if found
        if(Error::count() > 0) {
            Error::print_all();
            exit;
        }
        
        //If there are no raised errors, resource is added successfully
        echo "Resource added successfully";

        return true;
    }

    /* Setter methods */
    
    public function SetTitle($title) {
        $this->title = $title;
    }

    public function SetResourceType($resource_type) {
        $this->resource_type = $resource_type;
    }

    public function SetKeywords($keywords) {
        $this->keywords = $keywords;
    }

    public function SetAuthors($authors) {
        $this->authors = $authors;
    }

    public function SetURL($url) {
        $this->url = $url;
    }

    public function SetDescription($description) {
        $this->description = $description;
    }

    protected function AddResource () {
        $sql = "CALL insert_resource ('{$this->title}', '{$this->resource_type}', '{$this->url}', '{$this->description}')";
        $result = $this->query($sql);
        
        if($result != true) {
            Error::raise(__FILE__,__LINE__,'spf_insert_resource');
            return false;
        }

        return true;
    }

    protected function AddKeywords () {
            
        $sql = "CALL insert_keywords ('{$this->keywords}', '{$this->title}')";
        $result = $this->query($sql);

        if(!$result) {
            Error::raise(__FILE__,__LINE__,'spf_insert_keywords');
            return false;
        }

        return true;
    }

    protected function AddAuthors () {
        $sql ="CALL insert_authors('{$this->authors}', '{$this->title}')";
        $result = $this->query($sql);

        if(!$result) {
            Error::raise(__FILE__,__LINE__,'spf_insert_authors');
            return false;
        }

        return true;
    }
    
    
    //Check whether resource title already exists
    public function IsTitleDuplicate($title) {
        $sql = "SELECT COUNT(title) FROM resource WHERE title LIKE '{$title}'";
        $result = $this->query($sql);
        $row = $result->fetch_array();
        
        if($row[0] < 1) {
            //Title does not exist, return false
            return false;
        }
        
        //Title exists, return true
        return true;
    }
}

$AddingEntry = new AddingEntry;