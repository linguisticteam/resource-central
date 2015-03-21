<?php
/* Don't allow direct access */
//defined('START') or die();

require_once(dirname(dirname(__FILE__)) . '/lib/config.php');
require_once(dirname(dirname(__FILE__)) . '/controllers/error.php');

class Database extends mysqli {
    protected $Error;

    public function __construct(Error $Error) {
        parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        //pass the Error class so that we can use it
        $this->Error = $Error;
    }
}

$Database = new Database($Error);

class AddEntry extends Database {
    private $title;
    private $resource_type;
    private $url;
    private $authors;
    private $keywords;
    private $description;

    public function Commit() {
        $duplicate_title = $this->IsTitleDuplicate($title);

        if($duplicate_title) {
            global $Error;
            $Error->raise('TitleAlreadyExists');
            return false;
        }

        $this->AddResource();
        $this->AddKeywords();
        $this->AddAuthors();

        echo "Resource added successfully";

        return true;
    }

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
    
    //Check whether resource title already exist
    protected function IsTitleDuplicate() {
        $sql = "SELECT COUNT(title) FROM resource WHERE title LIKE '{$this->title}'";
        $result = $this->query($sql);
        $row = $result->fetch_array();
        
        if($row[0] > 0) {
            //Title exists, return true
            $this->raise('TitleAlreadyExists');
            return true;
        }

        //Title does not exist, return false
        return false;
    }

    protected function AddResource () {
        $sql = "CALL insert_resource ('{$this->title}', '{$this->resource_type}', '{$this->url}', '{$this->description}')";
        $result = $this->query($sql);
        $affected_rows = $this->affected_rows();
    
        if($affected_rows == 0) {
            // ToDo: output message probably through SESSION
            echo "insert_resource procedure failed";
            return;
        }
    }

    protected function AddKeywords () {
        $pieces = explode(",", $this->keywords);
        
        foreach($pieces as $piece) {
            
            $piece = trim($piece);
            
            //Add the keyword name if it's a new keyword
            $sql = "CALL insert_keyword ('{$piece}')";
            $this->query($sql);
            
            //Add the keyword-resource relations
            $sql = "CALL insert_keyword_xref ('{$this->title}', '{$piece}')";
            $result = $this->query($sql);
            
            if(!$result) {
                echo "Could not add keywords for the resource";
                return;
            }
        }
    }

    protected function AddAuthors () {
        $sql ="CALL insert_authors('{$this->authors}', '{$this->$title}')";
        $result = $this->query($sql);
        if(!$result) {
            echo "Could not add authors to the database";
            return;
        }
    }
}
