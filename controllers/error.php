<?php

class Error {
    private $error_descriptions = array();
    private $raised_errors = array();
    private $temp;

    public function __construct() {
        $this->error_descriptions['ContainsComma'] = "Resource Author Name contains reserved character: ','";
        $this->error_descriptions['SelectAuthorType'] = "Please select an Author Type";
        $this->error_descriptions['SpecifyResourceAuthor'] = "Please specify a Resource Author";
    }

    public function raise($error_label) {
        $this->raised_errors[] = $this->error_descriptions[$error_label];
    }
}

$Error = new Error();