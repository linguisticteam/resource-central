<?php

class CPagination {
    //Dependencies
    private $Database;
    
    public $current_page;
    public $next_page;
    public $previous_page;
    public $total_pages;
    public $resources_per_page;
    
    //Variables for using the MySQL LIMIT
    public $limit_start;
    public $limit_end;
    
    public function __construct(Database $Database) {
        $this->Database = $Database;
        
        $this->SetCurrentPage();
        $this->SetResourcesPerPage();
        $this->SetTotalPages();
        $this->SetNextPage();
        $this->SetPreviousPage();
        $this->SetMysqlLimits();
    }
    
    /* Set the current page */
    private function SetCurrentPage() { 
        //If current page is above zero, sanitize and accept that value
        if(!empty($_GET['page']) && $_GET['page'] > 0) {
            $this->current_page = (int) $_GET['page'];
            $this->next_page = $this->current_page + 1;
            $this->previous_page = $this->current_page - 1;
        }

        //If current page is zero or negative (i.e. invalid value), set current page to 1
        else {
            $this->current_page = 1;
        }
    }
    
    private function SetResourcesPerPage() {
        //ToDo: the user would be able to select this
        //ToDo: send a cookie to the browser to remember this value
        $this->resources_per_page = 1;
    }
    
    private function SetTotalPages() {
        $total_num_resources = (int) $this->Database->GetTotalNumResources();
        $this->total_pages = $total_num_resources / $this->resources_per_page;
    }
    
    /* Set the next page */
    private function SetNextPage() {
        //Set next page only if current page is not the last one
        if($this->total_pages > $this->current_page) {
            $this->next_page = $this->current_page + 1;
        }
    }
    
    /* Set the previous page */
    private function SetPreviousPage() {
        //Set the previous page only if current page is above 1
        if($this->current_page > 1) {
            $this->previous_page = $this->current_page - 1;
        }
    }
    
    private function SetMysqlLimits() {
        //The LIMIT start is the current page minus 1 times the number of resources per page
        $this->limit_start = ($this->current_page -1) * $this->resources_per_page;
        
        //The LIMIT end is the current page times the number of resources per page
        $this->limit_end = $this->current_page * $this->resources_per_page;
    }
}