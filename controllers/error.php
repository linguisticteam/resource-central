<?php

class Error {
    private static $error_descriptions = array();
    public static $raised_errors = array();

    public function __construct() {
        //$this->error_descriptions['CannotConnecttoDB'] = "Could not connect to database: " . mysqli_errno($connection);
        self::$error_descriptions['ContainsComma']           = "Resource Author Name contains reserved character: ','";
        self::$error_descriptions['SelectAuthorType']        = "Please select an Author Type";
        self::$error_descriptions['SpecifyResourceAuthor']   = "Please specify a Resource Author";
        self::$error_descriptions['spf_insert_authors']      = "Stored Procedure Failed: insert_authors";
        self::$error_descriptions['spf_insert_resource']     = "Stored Procedure Failed: insert_resource";
        self::$error_descriptions['spf_insert_keyword']      = "Stored Procedure Failed: insert_keyword";
        self::$error_descriptions['spf_insert_keyword_xref'] = "Stored Procedure Failed: insert_keyword_xref";
        self::$error_descriptions['TitleAlreadyExists']      = "A resource with this title already exists";
    }

    public static function raise($error_label) {
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