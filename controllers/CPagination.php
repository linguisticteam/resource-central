<?php

class CPagination {
    //Dependencies
    private $MDatabase;
    
    public $current_page;
    public $next_page;
    public $previous_page;
    public $total_pages;
    public $resources_per_page;
    
    //Variables for using the MySQL LIMIT
    public $limit_offset;
    public $limit_maxNumRows;
    
    public function __construct(MDatabase $MDatabase, $search_query = NULL) {
        $this->MDatabase = $MDatabase;
        
        $this->SetResourcesPerPage();
        $this->SetTotalPages($search_query);
        $this->SetCurrentPage();
        $this->SetNextPage();
        $this->SetPreviousPage();
        $this->SetMysqlLimits();
    }
    
    /* Set the total page number */
    private function SetTotalPages($search_query = NULL) {
        
        //If there is a search query specified, get the number of results from it 
        if($search_query !== NULL) {
            $num_resources = (int) $this->MDatabase->GetTotalNumResourcesForSearchQuery($search_query);
        }
        
        //Else get the total number of resources in the database
        else {
            $num_resources = (int) $this->MDatabase->GetTotalNumResources();
        }
        
        //Divide the above by how many resources per page will be shown
        $absolute_value = $num_resources / $this->resources_per_page;
        
        //Round up and have the last page constitute of the remainder (if any) of the division above
        $this->total_pages = ceil($absolute_value);
    }
    
    /* Set the next page */
    private function SetNextPage() {
        //If current page is not the last one, set the next page
        if($this->total_pages > $this->current_page) {
            $this->next_page = $this->current_page + 1;
        }
        
        //Else unset the next page
        else{
            $this->next_page = '';
        }
    }
    
    /* Set the current page */
    private function SetCurrentPage() { 
        //If current page is set and is above zero, sanitize and accept that value
        if(!empty($_GET['page']) && $_GET['page'] > 0) {
            $this->current_page = (int) $_GET['page'];
        }

        //If current page is zero or negative (i.e. invalid value), set current page to 1
        else {
            $this->current_page = 1;
        }
    }
    
    /* Set the number of resources we show per page */
    private function SetResourcesPerPage() {
        //ToDo: the user would be able to select this
        //ToDo: send a cookie to the browser to remember this value
        $this->resources_per_page = 1;
    }
    
    /* Set the previous page */
    private function SetPreviousPage() {
        //Set the previous page only if current page is above 1
        if($this->current_page > 1) {
            $this->previous_page = $this->current_page - 1;
        }
    }
    
    private function SetMysqlLimits() {
        //The LIMIT offset is the current page minus 1 times the number of resources per page
        $this->limit_offset = ($this->current_page -1) * $this->resources_per_page;
        
        //The LIMIT maximum number of returned rows is the same as resources per page
        $this->limit_maxNumRows = $this->resources_per_page;
    }
}