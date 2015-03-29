<?php
require_once('error.php');
require_once(dirname(dirname(__FILE__)) . '/models/database.php');

class FormProcessor {
    //Dependencies
    private $Database;
    private $AddingEntry;
    
    //array to hold the inputted Resource Author(s) and Author Type(s)
    private $authors_array = array();
    private $author_types = array();

    public function __construct (Database $Database, AddingEntry $AddingEntry) {
        $this->Database = $Database;
        $this->AddingEntry = $AddingEntry;
    }
    
    /* Methods to get and process values supplied through an HTML form */
    
    public function GetValidatedTitle() {
        $title_exists = $this->isFieldPresent('title');
        
        if(!$title_exists) {
            Error::raise(__FILE__,__LINE__,'TitleNotSpecified');
            return;
        }
        
        $title = $this->getEscapedField('title');   
        $is_duplicate = $this->AddingEntry->IsTitleDuplicate($title);
        
        if($is_duplicate) {
            Error::raise(__FILE__,__LINE__,'TitleAlreadyExists');
            return;
        }
        
        return $title;
    }
    
    public function GetValidatedResourceType() {
        $resource_type_exists = $this->isFieldPresent('resource_type');
        
        if(!$resource_type_exists) {
            Error::raise(__FILE__, __LINE__, 'SelectResourceType');
            return;
        }
        
        $resource_type = $this->getEscapedField('resource_type');
        $resource_types = $this->Database->GetTypes('resource_type', 'name');
        
        //Make sure that the Resource Type is one of the predetermined values
        if(!in_array($resource_type, $resource_types, true)) {
            Error::raise(__FILE__, __LINE__, 'ResourceTypeIncorrectValue');
            return;
        }
        
        return $resource_type;
    }
    
    public function GetValidatedUrl() {
        $url_exists = $this->isFieldPresent('url');
        
        if(!$url_exists) {
            Error::raise(__FILE__, __LINE__, 'SpecifyResourceURL');
            return;
        }
        
        $url = $this->getEscapedField('url');
        return $url;
    }

    public function GetValidatedAuthors () {
        //Loop and on each iteration, check for values present
        //in the Resource Author and Author Type fields 
        for($i = 0; true; $i++) {
            $resource_author_exists = $this->isFieldPresent('resource_author_' . $i);
            $author_type_exists = $this->isFieldPresent('author_' . $i . '_type');
    
            if(!$resource_author_exists &&
                   !$author_type_exists) {
                // Neither author name nor author type exist.
                break;
            }
            elseif( $resource_author_exists &&
                   !$author_type_exists) {
                // Only author name exists.
                Error::raise(__FILE__,__LINE__,'SelectAuthorType');
            }
            elseif(!$resource_author_exists &&
                    $author_type_exists) {
                // Only author type exists.
                Error::raise(__FILE__,__LINE__,'SpecifyResourceAuthor');
            }
            elseif( $resource_author_exists &&
                $author_type_exists) {
                // Both author name and author type exist.
                
                //Put Resource Author and Author Type in $authors_array,
                //while preserving the relationship between the two values
                $this->AddAuthorToArray($i);        
            }
        }
        
        //Put all collected Resouce Author-Auhtor Type couples
        //in a string, divided in sections by "|"
        $authors = implode('|', $this->authors_array);
        return $authors;
    }
    
    public function GetValidatedKeywords() {
        $keywords_exist = $this->isFieldPresent('keywords');
        
        if(!$keywords_exist) {
            Error::raise(__FILE__, __LINE__, 'KeywordsAreRequired');
            return;
        }
        
        $keywords = $this->getEscapedField('keywords');
        
        //Trim each individual keyword
        $trimmed_keywords = array();
        $pieces = explode(',', $keywords);
        
        foreach($pieces as $piece) {
            $piece = trim($piece);
            
            //Keyword cannot be empty
            if(!empty($piece)) {
                $trimmed_keywords[] = $piece;
            }
        }
        
        //Put the trimmed keywords back into a string
        $keywords = implode(',', $trimmed_keywords);
        
        return $keywords;
    }

    public function GetValidatedDescription() {
        $description_exists = $this->isFieldPresent('description');
        
        if(!$description_exists) {
            Error::raise(__FILE__, __LINE__, 'ProvideDescription');
            return;
        }
        
        $description = $this->getEscapedField('description');
        return $description;
    }
    
    public function AddAuthorToArray ($Index) {

        //Get the values in temporary variables so we can then do checking and concatenate them
        $tempResourceAuthor = $this->getEscapedField('resource_author_' . $Index);
        $tempAuthorType = $this->getEscapedField('author_' . $Index . '_type');
        
        //Resouce Author cannot contain any commas
        if ($this->containsComma($tempResourceAuthor) == true) {
            Error::raise(__FILE__,__LINE__,'ContainsComma');
        }

        //Get the predetermined Author Types if we haven't already
        if(!$this->author_types) {
            $this->author_types = $this->Database->GetTypes('entity_type', 'name');
        }
        
        //Make sure that Author Type is one of the values that we have predetermined
        if(!in_array($tempAuthorType, $this->author_types, true)) {
            Error::raise(__FILE__, __LINE__, 'AuthorTypeIncorrectValue');
            return;
        }
        
        //Concatenate the values and put them together in the array,
        //so as to preserve the relationship between the two
        $this->authors_array[$Index] = $tempResourceAuthor . ',' . $tempAuthorType;
    }

    public function isFieldPresent ($Field) {
        return !empty($_POST[$Field]);
    }

    public function getEscapedField ($Field) {
        $trimmedField = $this->getTrimmedField($Field);
        return $this->escapeString($trimmedField);
    }
    
    public function getTrimmedField ($Field) {
        return trim($_POST[$Field]);
    }

    public function escapeString ($String) {
        $db = $this->Database;
        return $db->real_escape_string($String);
    }
    
    
    /* Checks whether a string contains any commas */
    
    public function containsComma ($String) {
        // Check if $String contains commas
        $Result = strstr($String,',');

        // If it does not, then return false
        if ($Result == false) {
            return false;
        }

        // If it does, then return true
        return true;
    }
}

$FormProcessor = new FormProcessor($Database, $AddingEntry);