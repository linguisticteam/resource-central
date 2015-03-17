<?php

class Errors {
    const containsReservedCharacter = "20: Resource Author Name contains reserved character: ','\n";
    const AUTHOR_TYPE = 1;
}

class Error {
    private $description_array;
    private $raise_count_array;

    public function __construct() {
        $array['ErrorOne'] = 'This is an error message';
        $array['ErrorTwo'] = 'This is another error message';
        $array['ErrorThree'] = "There's like tons of error messages here!";
    }

    public function Raise($error_label) {

    }
}