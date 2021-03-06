<?php

class MAddingEntry extends MDatabase {
    //Dependencies
    private $Error;
    
    private $title;
    private $resource_type;
    private $url;
    private $authors;
    private $keywords;
    private $publishing_date;
    private $description;

    public function __construct(Error $Error) {
        parent::__construct($Error);
        $this->Error = $Error;
    }
    
    public function SetProperties($title, $resource_type, $url, $authors, $keywords, $publishing_date, $description) {
        //Call the setter methods one by one
        $this->SetTitle($title);
        $this->SetResourceType($resource_type);
        $this->SetURL($url);
        $this->SetAuthors($authors);
        $this->SetKeywords($keywords);
        $this->SetPublishingDate($publishing_date);
        $this->SetDescription($description);
    }

    public function InsertToDb() {

        //Start transaction
        $this->autocommit(FALSE);
        
        $this->AddResource();
        $this->AddKeywords();
        $this->AddAuthors();
        
        //Roll back changes if there were any raised errors
        if($this->Error->count() > 0) {
            $this->rollback();
            return false;
        } 
            
        //If there were no raised errors, commit
        $this->commit();
        
        //End transaction
        $this->autocommit(TRUE);

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
    
    public function SetPublishingDate($publishing_date) {
        $this->publishing_date = $publishing_date;
    }

    public function SetDescription($description) {
        $this->description = $description;
    }

    protected function AddResource () {
        $sql = "CALL insert_resource ('{$this->title}', '{$this->resource_type}', '{$this->url}',"
        . " '{$this->publishing_date}', '{$this->description}')";
        $result = $this->query($sql);
        
        if($result != true) {
            $this->Error->raise(__FILE__,__LINE__,'spf_insert_resource');
            return false;
        }

        return true;
    }

    protected function AddKeywords () {
            
        $sql = "CALL insert_keywords ('{$this->keywords}', '{$this->title}')";
        $result = $this->query($sql);

        if(!$result) {
            $this->Error->raise(__FILE__,__LINE__,'spf_insert_keywords');
            return false;
        }

        return true;
    }

    protected function AddAuthors () {
        $sql ="CALL insert_authors('{$this->authors}', '{$this->title}')";
        $result = $this->query($sql);

        if(!$result) {
            $this->Error->raise(__FILE__,__LINE__,'spf_insert_authors');
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
