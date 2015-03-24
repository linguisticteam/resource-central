<?php

class Error {
    private static $error_descriptions = array();
    public static $raised_errors = array();

    public function __construct() {
        //$this->error_descriptions['CannotConnecttoDB'] = "Could not connect to database: " . mysqli_errno($connection);
        self::$error_descriptions['ContainsComma'] = "Resource Author name contains reserved character: ','";
        self::$error_descriptions['TitleNotSpecified'] = "Please specify a Title";
        self::$error_descriptions['TitleAlreadyExists'] = "A resource with this title already exists";
        self::$error_descriptions['SelectAuthorType'] = "Please select an Author Type";
        self::$error_descriptions['SpecifyResourceAuthor'] = "Please specify a Resource Author";
        
    }

    public static function raise($error_label) {
        self::$raised_errors[] = self::$error_descriptions[$error_label];
    }
}

$Error = new Error();