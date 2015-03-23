<?php

class Error {
    private static $error_descriptions = array();
    public static $raised_errors = array();

    public function __construct() {
        // Errors in PHP (Category#00):

        // Errors in data convention (Category#01):
        self::$error_descriptions['ContainsComma']           = "Error#01-01: Resource Author Name contains reserved character: ','";
        self::$error_descriptions['SelectAuthorType']        = "Error#01-02: Please select an Author Type";
        self::$error_descriptions['SpecifyResourceAuthor']   = "Error#01-03: Please specify a Resource Author";
        self::$error_descriptions['TitleAlreadyExists']      = "Error#01-04: A resource with this title already exists";

        // Errors when interacting with database (Category#02):
        //self::$error_descriptions['CannotConnectToDB'] = "Error#02-01: Could not connect to database: " . mysqli_errno($connection);

        // Errors when calling stored procedure in database (Category#03):
        self::$error_descriptions['spf_insert_authors']      = "Error#03-01: Stored Procedure Failed: insert_authors";
        self::$error_descriptions['spf_insert_resource']     = "Error#03-02: Stored Procedure Failed: insert_resource";
        self::$error_descriptions['spf_insert_keyword']      = "Error#03-03: Stored Procedure Failed: insert_keyword";
        self::$error_descriptions['spf_insert_keyword_xref'] = "Error#03-04: Stored Procedure Failed: insert_keyword_xref";
    }

    public static function raise($error_label) {
        // Add error to list
        self::$raised_errors[] = self::$error_descriptions[$error_label];
    }

    public static function clear_all() {
        self::$raised_errors = array();
    }

    public static function count() {
        return count(self::$raised_errors);
    }
}

$Error = new Error();