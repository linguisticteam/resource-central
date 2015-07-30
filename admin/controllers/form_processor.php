<?php
defined('START') or die();

class FormProcessor {
    //Dependencies
    private $MDatabase;
    private $MAddingEntry;
    private $Error;
    
    //array to hold the inputted Resource Author(s) and Author Type(s)
    private $authors_array = array();
    private $predetermined_author_types = array();

    public function __construct (MDatabase $MDatabase, MAddingEntry $MAddingEntry, Error $Error) {
        $this->MDatabase = $MDatabase;
        $this->MAddingEntry = $MAddingEntry;
        $this->Error = $Error;
    }
    
    /* Methods to get and process values supplied through an HTML form */
    
    public function GetValidatedTitle() {
        $title_exists = $this->isFieldPresent('title');
        
        if(!$title_exists) {
            $this->Error->raise(__FILE__,__LINE__,'TitleNotSpecified');
            return;
        }
        
        $title = $this->getEscapedField('title');   
        $is_duplicate = $this->MAddingEntry->IsTitleDuplicate($title);
        
        if($is_duplicate) {
            $this->Error->raise(__FILE__,__LINE__,'TitleAlreadyExists');
            return;
        }
        
        return $title;
    }
    
    public function GetValidatedResourceType() {
        $resource_type_exists = $this->isFieldPresent('resource_type');
        
        if(!$resource_type_exists) {
            $this->Error->raise(__FILE__, __LINE__, 'SelectResourceType');
            return;
        }
        
        $resource_type = $this->getEscapedField('resource_type');
        $predetermined_resource_types = $this->MDatabase->GetTypes('resource_type', 'name');
        
        //Make sure that the Resource Type is one of the predetermined values
        if(!in_array($resource_type, $predetermined_resource_types, true)) {
            $this->Error->raise(__FILE__, __LINE__, 'ResourceTypeIncorrectValue');
            return;
        }
        
        return $resource_type;
    }
    
    public function GetValidatedUrl() {
        $url_exists = $this->isFieldPresent('url');
        
        if(!$url_exists) {
            $this->Error->raise(__FILE__, __LINE__, 'SpecifyResourceURL');
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
                $this->Error->raise(__FILE__,__LINE__,'SelectAuthorType');
            }
            elseif(!$resource_author_exists &&
                    $author_type_exists) {
                // Only author type exists.
                $this->Error->raise(__FILE__,__LINE__,'SpecifyResourceAuthor');
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
            $this->Error->raise(__FILE__, __LINE__, 'KeywordsAreRequired');
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
    
    /* This function validates the publishing date but allows for optional year, month and day */
    public function GetValidatedPublishingDate() {
        //Do anything only if year is present
        if(!$this->isFieldPresent('publishing_year')) {
            return '';
        }
        
        /* If year is present, carry on */
        
        //Get year value
        $year = $this->getTrimmedField('publishing_year');

        //Check if month is present
        if($this->isFieldPresent('publishing_month')) {
            $month = $this->getTrimmedField('publishing_month');

            //If month is present, check if day is present
            if($this->isFieldPresent('publishing_day')) {
                $day = $this->getTrimmedField('publishing_day');
            }
            //If day is not present, enter a dummy value for it
            else {
                $day = 1;
                $day_not_present = true;
            }
        }
        //If month is not present, enter a dummy value for month and day
        else {
            $month = 1;
            $month_not_present = true;
            $day = 1;
            $day_not_present = true;
        }

        //Now that we have some values for year, month and day, let's validate them
        //Note: the validation is supposed to pass even if only year or year+month are present
        $is_date_valid = checkdate($month, $day, $year);

        //If date provided is not valid, raise error and finish
        if(!$is_date_valid) {
            $this->Error->raise(__FILE__, __LINE__, 'InvalidDate');
            return;
        }

        //Assign either the validated month/day value or '00' (which means 'Unknown')
        $validated_month = $month_not_present ? '00' : $month;
        $validated_day = $day_not_present ? '00' : $day;
        
        $validated_date = $year . '-' . $validated_month . '-' . $validated_day;
        return $validated_date;
    }

    public function GetValidatedDescription() {
        $description_exists = $this->isFieldPresent('description');
        
        if(!$description_exists) {
            $this->Error->raise(__FILE__, __LINE__, 'ProvideDescription');
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
            $this->Error->raise(__FILE__,__LINE__,'ContainsComma');
        }

        //Get the predetermined Author Types if we haven't already
        if(!$this->predetermined_author_types) {
            $this->predetermined_author_types = $this->MDatabase->GetTypes('author_type', 'name');
        }
        
        //Make sure that Author Type is one of the values that we have predetermined
        if(!in_array($tempAuthorType, $this->predetermined_author_types, true)) {
            $this->Error->raise(__FILE__, __LINE__, 'AuthorTypeIncorrectValue');
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
        return $this->MDatabase->real_escape_string($String);
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
