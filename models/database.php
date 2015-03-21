<?php
/* Don't allow direct access */
//defined('START') or die();

require_once(dirname(dirname(__FILE__)) . '/lib/config.php');
require_once(dirname(dirname(__FILE__)) . '/controllers/error.php');

class Database extends mysqli {
    private $Error;
    public $connection;
    
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
    
    //
    
    //Check whether resource title already exist
    public function TitleExists($title) {  
        $sql = "SELECT COUNT(title) FROM resource WHERE title LIKE '{$title}'";
        $result = $this->query($sql);
        $row = $result->fetch_array();
        if($row[0] > 0) {
            //Title exists, return true
            $this->raise('TitleAlreadyExists');
            return true;
        } else {
            //Title does not exist, return false
            return false;
        }
    }
}

$AddEntry = new AddEntry($Error);
$AddEntry->TitleExists('title');

function add_entry($connection, $title, $resource_type, $url, $authors, $keywords, $description) {
    //Return with error if resource title already exist
    $sql = "SELECT COUNT(title) FROM resource WHERE title LIKE '{$title}'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($result);
    if($row[0] > 0) {
        echo 'A resource with this title already exists';
        return;
    }
    
    //Add resource
    $sql = "CALL insert_resource ('{$title}', '{$resource_type}', '{$url}', '{$description}')";
    $result = mysqli_query($connection, $sql);
    if(($rows = mysqli_affected_rows($connection)) == 0) {
        // ToDo: output message probably through SESSION
        echo "insert_resource procedure failed";
        return;
    }
    
    //Add keywords
    $pieces = explode(",", $keywords);
    foreach($pieces as $piece) {
        $piece = trim($piece);
        //Add the keyword name if it's a new keyword
        $sql = "CALL insert_keyword ('{$piece}')";
        mysqli_query($connection, $sql);
        //Add the keyword-resource relations
        $sql = "CALL insert_keyword_xref ('{$title}', '{$piece}')";
        $result = mysqli_query($connection, $sql);
        if(!$result) {
            echo "Could not add keywords for the resource";
            return;
        }
    }
    
    //Add authors
    $sql ="CALL insert_authors('{$authors}', '{$title}')";
    $result = mysqli_query($connection, $sql);
    if(!$result) {
        echo "Could not add authors to the database";
        return;
    }
    
       echo "Resource added successfully";

    //$video_id = get_video_id($title);

    //add_part($connection,$title,$url,"NULL");
}

