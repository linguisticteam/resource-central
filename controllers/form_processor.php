<?php
require_once('error.php');
require_once(dirname(dirname(__FILE__)) . '/models/database.php');

class FormProcessor {
    //the instantiation of the Error class
    private $Error;
    private $Database;
    //arrays to hold the inputted Resource Author(s) and Author Type(s)
    private $authors_array = array();

    public function __construct (Database $Database) {
        //pass the Error class so that we can use it
        //$this->Error = $Error;
        $this->Database = $Database;
    }

    public function AddAuthorToArray ($Index) {

        //Get the values in temporary variables so we can then do checking and concatenate them
        $tempResourceAuthor = $this->getEscapedField('resource_author_' . $Index);
        $tempAuthorType = $this->getEscapedField('author_' . $Index . '_type');
        
        //Remove whitespace
        $tempResourceAuthor = trim($tempResourceAuthor);
        $tempAuthorType = trim($tempAuthorType);
        
        //Resouce Author cannot contain any commas
        if ($this->containsComma($tempResourceAuthor) == true) {
            Error::raise('ContainsComma');
        }

        //ToDo: make sure that Author Type is one of the values that we have predetermined
        
        //Concatenate the values and put them together in the array,
        //so as to preserve the relationship between the two
        $this->authors_array[$Index] = $tempResourceAuthor . ',' . $tempAuthorType;
    }
    

    public function GetAuthors () {
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
                Error::raise('SelectAuthorType');
            }
            elseif(!$resource_author_exists &&
                    $author_type_exists) {
                // Only author type exists.
                Error::raise('SpecifyResourceAuthor');
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

    
    /* Static Functions */

    public function isFieldPresent ($Field) {
        return !empty($_POST[$Field]);
    }

    public function getField ($Field) {
        return $_POST[$Field];
    }

    public function getEscapedField ($Field) {
        $tempField = $this->getField($Field);
        return $this->escapeString($tempField);
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

$FormProcessor = new FormProcessor($Database);