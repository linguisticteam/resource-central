<?php

class VDisplayKeywords {
    //Dependencies
    private $MDatabase;
    
    public function __construct(MDatabase $MDatabase) {
        $this->MDatabase = $MDatabase;
    }
    
    /* Display the keywords in the order in which they are most commonly found in the database */
    public function DisplayKeywordsByPopularity() {
        $result = $this->MDatabase->GetKeywordsByPopularity();
        
        if(!$result) {
            return;
        }
        
        //Start the output
        $output = 'Popular keywords:<br>';
        
        while($row = $result->fetch_array()) {
            $keyword = htmlspecialchars($row[0]);
            $output .= "<a href='/?q={$keyword}'>{$keyword}</a>";
            $output .= ', ';
        }
        
        //Remove the trailing comma and space from the last iteration
        $output = rtrim($output, ', ');
        
        $output .= ' (see all)';
        
        //Echo the result
        echo $output;
    }
}