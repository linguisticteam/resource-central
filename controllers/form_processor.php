<?php

class FieldType {
    const RESOURCE_AUTHOR = 0;
    const AUTHOR_TYPE = 1;
}

class FormProcessor {
    //the instantiation of the Error class
    private $Error;
    //arrays to hold the inputted Resource Author(s) and Author Type(s)
    private $resource_author_array = [];
    private $author_type_array = [];

    public function __construct (Error $Error) {
        //pass the Error class so that we can use it
        $this->Error = $Error;
    }

    public function addFieldToArray ($FieldType, $Index) {
        
        
        switch($FieldType) {
            case FieldType::RESOURCE_AUTHOR:
                //process Resource Author
                $tempResourceAuthor = FormProcessor::getEscapedField('resource_author_' . $Index);
                break;
            case FieldType::AUTHOR_TYPE:
                //process Author Type
                $tempAuthorType = FormProcessor::getEscapedField('author_' . $Index . '_type');
                break;
        }

        if (FormProcessor::containsComma($tempResourceAuthor) == true) {
            $this->Error->raise('ContainsComma');
        }

        // Ok
        $resource_author_array[] = $tempResourceAuthor;
        return true;
        

        switch($FieldType) {
            case FieldType::RESOURCE_AUTHOR:
                //process Resource Author
                $tempResourceAuthor = FormProcessor::getField('resource_author_' . $Index);
                break;
            case FieldType::AUTHOR_TYPE:
                //process Author Type
                $tempResourceAuthor = FormProcessor::getField('author_' . $Index . '_type');
                break;
        }
    }
    

    public function addAuthorTypeToArray ($Index) {
        $author_type_array[] = FormProcessor::getEscapedField('author_' . $Index . '_type');
    }

    
    /* Static Functions */

    public static function isFieldPresent ($Field) {
        return !empty($_POST[$Field]);
    }

    
    public static function getField ($Field) {
        return $_POST[$Field];
    }

    
    public static function getEscapedField ($Field) {
        $tempField = FormProcessor::getField($Field);
        return FormProcessor::escapeString($tempField);
    }

    
    public static function escapeString ($String) {
        global $connection;
        return mysqli_real_escape_string($connection, $String);
    }

    
    /* Checks whether a string contains any commas */
    
    public static function containsComma ($String) {
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

$FormProcessor = new FormProcessor($Error);