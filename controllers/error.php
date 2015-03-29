<?php

class ErrorTemplate {
    private $category;    // Category number
    private $number;      // Number in category
    private $technical;   // Short technical information
    private $description; // Expanded information for user

    private $file;        // File name where error occurred
    private $line;        // Line number in file where error occurred

    /* Constructors */

    public function __construct($category,$number,$technical,$description) {
        $this->category    = $category;
        $this->number      = $number;
        $this->technical   = $technical;
        $this->description = $description;
        $this->file        = "";
        $this->line        = 0;
    }

    /* Set Functions */

    public function SetFile($file) {
        $this->file = $file;
    }

    public function SetLine($line) {
        $this->line = $line;
    }

    /* Get Functions */

    public function GetCategory() {
        return $this->category;
    }

    public function GetNumber() {
        return $this->number;
    }

    public function GetTechnical() {
        return $this->technical;
    }

    public function GetDescription() {
        return $this->description;
    }

    public function GetFile() {
        return $this->file;
    }
    
    public function GetLine() {
        return $this->line;
    }

    /* Printing Functions */

    public function PrintTechnical() {
        echo("Error#" .
            $this->category .
            "-" .
            $this->number .
            ":" .
            $this->technical .
            "\n" .
            "File: '" .
            $this->file .
            "'\n" .
            "Line: " .
            $this->line .
            "\n");
    }

    public function PrintDescription() {
        echo($this->description);
    }
}

class Error {
    private static $templates = array();
    private static $raised_errors = array();

    public function __construct() {
        // Errors in PHP (Category#00):
        
        // Errors in data convention (Category#01):
        self::$templates['ContainsComma']           = new ErrorTemplate(01,01,"TECHNICAL","Resource Author Name contains reserved character: ','");
        self::$templates['SelectAuthorType']        = new ErrorTemplate(01,02,"TECHNICAL","Please select an Author Type");
        self::$templates['SpecifyResourceAuthor']   = new ErrorTemplate(01,03,"TECHNICAL","Please specify a Resource Author");
        self::$templates['TitleAlreadyExists']      = new ErrorTemplate(01,04,"TECHNICAL","A resource with this title already exists");
        self::$templates['TitleNotSpecified']   = new ErrorTemplate(01,05,"TECHNICAL", "Resource title is not specified");
        self::$templates['KeywordsAreRequired'] = new ErrorTemplate(01,06,"TECHNICAL", "Please specify some keywords");
        self::$templates['AuthorTypeIncorrectValue'] = new ErrorTemplate(01,07,"TECHNICAL", "Author Type is an incorrect value");
        self::$templates['SelectResourceType'] = new ErrorTemplate(01,08,"TECHNICAL", "Please select a Resource Type");
        self::$templates['ResourceTypeIncorrectValue'] = new ErrorTemplate(01,09,"TECHNICAL", "Resource Type is an incorrect value");
        self::$templates['SpecifyResourceURL'] = new ErrorTemplate(01,10,"TECHNICAL", "Please specify a URL for the Resource");
        
        // Errors when interacting with database (Category#02):
        //self::$templates['CannotConnectToDB']       = new ErrorTemplate(02,01,"TECHNICAL","Could not connect to database: " . mysqli_errno($connection));
        self::$templates['GetTypesMethodFailed']    = new ErrorTemplate(02,01,"Database->GetTypes() failed", "DESCRIPTION");
        
        // Errors when calling stored procedure in database (Category#03):
        self::$templates['spf_insert_authors']      = new ErrorTemplate(03,01,"Stored Procedure Failed: insert_authors","DESCRIPTION");
        self::$templates['spf_insert_resource']     = new ErrorTemplate(03,02,"Stored Procedure Failed: insert_resource","DESCRIPTION");
        self::$templates['spf_insert_keywords']      = new ErrorTemplate(03,03,"Stored Procedure Failed: insert_keywords","DESCRIPTION");
    }

    public static function raise($file,$line,$error_key) {

        $error = self::$templates[$error_key];

        $error->SetFile($file);
        $error->SetLine($line);

        self::$raised_errors[] = $error;
    }

    public static function clear_all() {
        self::$raised_errors = array();
    }

    public static function count() {
        return count(self::$raised_errors);
    }

    public static function print_all() {
        foreach (self::$raised_errors as $error) {
            $error->PrintTechnical();
            $error->PrintDescription();
        }
    }
}

$Error = new Error();