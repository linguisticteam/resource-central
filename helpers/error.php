<?php

class ErrorTemplate {
    public $category;    // Category number
    public $number;      // Number in category
    public $technical;   // Short technical information
    public $description; // Expanded information for user

    public $file;        // File name where error occurred
    public $line;        // Line number in file where error occurred

    /* Constructors */

    public function __construct($category,$number,$technical = null,$description = null) {
        $this->category    = $category;
        $this->number      = $number;
        $technical == null ? $this->technical = '' : $this->technical = $technical;
        $description == null ? $this->description = '' : $this->description = $description;
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

    /* Info Retrieving Functions */

    public function TechnicalDataToString() {
        $compiled_string = "Error#" .
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
            "\n";

        return $compiled_string;
    }
}

class Error {
    //Dependencies
    private $Logger;
    
    private $templates = array();
    private $raised_errors = array();

    public function __construct(Logger $Logger) {
        //Inject Dependencies
        $this->Logger = $Logger;
        
        
        /* Initialize error templates */
        
        // Errors in PHP (Category#00):

        // Errors in data convention (Category#01):
        $this->templates['ContainsComma']              = new ErrorTemplate(01,01,null,"Resource Author Name contains reserved character: ','");
        $this->templates['SelectAuthorType']           = new ErrorTemplate(01,02,null,"Please select an Author Type");
        $this->templates['SpecifyResourceAuthor']      = new ErrorTemplate(01,03,null,"Please specify a Resource Author");
        $this->templates['TitleAlreadyExists']         = new ErrorTemplate(01,04,null,"A resource with this title already exists");
        $this->templates['TitleNotSpecified']          = new ErrorTemplate(01,05,null,"Resource title is not specified");
        $this->templates['KeywordsAreRequired']        = new ErrorTemplate(01,06,null,"Please specify some keywords");
        $this->templates['AuthorTypeIncorrectValue']   = new ErrorTemplate(01,07,null,"Author Type is an incorrect value");
        $this->templates['SelectResourceType']         = new ErrorTemplate(01,08,null,"Please select a Resource Type");
        $this->templates['ResourceTypeIncorrectValue'] = new ErrorTemplate(01,09,null,"Resource Type is an incorrect value");
        $this->templates['SpecifyResourceURL']         = new ErrorTemplate(01,10,null,"Please specify a URL for the Resource");
        $this->templates['ProvideDescription']         = new ErrorTemplate(01,11,null,"Please provide a description");
        
        // Errors when interacting with database (Category#02):
        //$this->templates['CannotConnectToDB']        = new ErrorTemplate(02,01,"TECHNICAL","Could not connect to database: " . mysqli_errno($connection));
        $this->templates['GetTypesMethodFailed']       = new ErrorTemplate(02,01,"Database->GetTypes() failed");
        $this->templates['GetResourcesMethodFailed'] = new ErrorTemplate(02, 02, "Database->GetResources() failed");
        $this->templates['GetKeywordsMethodFailed'] = new ErrorTemplate(02, 03, "Database->GetKeywords() failed");
        $this->templates['GetResourceURLMethodFailed'] = new ErrorTemplate(02, 04, "Database->GetResourceURL() failed");
        $this->templates['GetAuthorsMethodFailed'] = new ErrorTemplate(02, 05, "Database->Authors() failed");
        $this->templates['GetTotalNumResourcesMethodFailed'] = new ErrorTemplate(02, 06, "Database->GetTotalNumResources() failed");
        
        // Errors when calling stored procedure in database (Category#03):
        $this->templates['spf_insert_authors']         = new ErrorTemplate(03,01,"Stored Procedure Failed: insert_authors");
        $this->templates['spf_insert_resource']        = new ErrorTemplate(03,02,"Stored Procedure Failed: insert_resource");
        $this->templates['spf_insert_keywords']        = new ErrorTemplate(03,03,"Stored Procedure Failed: insert_keywords");
    }

    public function raise($file,$line,$error_key) {

        $error = $this->templates[$error_key];

        $error->SetFile($file);
        $error->SetLine($line);

        $this->raised_errors[] = $error;
        
        //Log to file if it's an internal error
        if($error->category == 02 || $error->category == 03) {
            $this->Logger->log_error($error->technical, $error->file, $error->line);
        }
    }

    public function clear_all() {
        $this->raised_errors = array();
    }

    public function count() {
        return count($this->raised_errors);
    }

    public function print_all() {
        foreach ($this->raised_errors as $error) {
            //echo($error->TechnicalDataToString());
            echo($error->GetDescription() . '<br>');
        }
    }
}