<?php
/* Don't allow direct access */
//defined('START') or die();

require_once(dirname(dirname(__FILE__)) . '/lib/config.php');
require_once(dirname(dirname(__FILE__)) . '/controllers/error.php');

class Database extends mysqli {

    public function __construct() {
        parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
}

$Database = new Database;

class AddEntry extends Database {
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
        $unique_title = $this->IsTitleUnique($title);

        if(!$unique_title) {
            Error::raise('TitleAlreadyExists');
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
    
    protected function IsTitleUnique() {
        $sql = "SELECT COUNT(title) FROM resource WHERE title LIKE '{$this->title}'";
        $result = $this->query($sql);

        $row = $result->fetch_array();

        if($row[0] > 0) {
            //Title exists, return false
            Error::raise('TitleAlreadyExists');
            return false;
        }

        //Title does not already exist in the database, return true
        return true;
    }

    protected function AddResource () {
        $sql = "CALL insert_resource ('{$this->title}', '{$this->resource_type}', '{$this->url}', '{$this->description}')";
        $result = $this->query($sql);

        $affected_rows = $this->affected_rows;

        if($affected_rows == 0) {
            Error::raise('spf_insert_resource');
            return false;
        }

        return true;
    }

    protected function AddKeywords () {
        //ToDo: Keywords cannot contain commas, we need to check for that
        $pieces = explode(",", $this->keywords);
        
        foreach($pieces as $piece) {
            
            $piece = trim($piece);
            
            //Add the keyword name if it's a new keyword
            $sql = "CALL insert_keyword ('{$piece}')";
            $result = $this->query($sql);
            
            if(!$result) {
                Error::raise('spf_insert_keyword');
                return false;
            }

            //Add the keyword-resource relations
            $sql = "CALL insert_keyword_xref ('{$this->title}', '{$piece}')";
            $result = $this->query($sql);
            
            if(!$result) {
                Error::raise('spf_insert_keyword_xref');
                return false;
            }
        }

        return true;
    }

    protected function AddAuthors () {
        $sql ="CALL insert_authors('{$this->authors}', '{$this->title}')";
        $result = $this->query($sql);
        
        if(!$result) {
            $Error::raise('spf_insert_authors');
            return false;
        }

        return true;
    }
}

$AddEntry = new AddEntry;