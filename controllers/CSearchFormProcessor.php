<?php
defined('START') or die();

class CSearchFormProcessor{
    
    public function ProcessForm() {

        //If there is something entered in the Search field 
        if(!empty($_POST['search_query'])) {
            
            //Trim and escape the user-submitted search query
            $search_query = trim($_POST['search_query']);
            $search_query = urlencode($search_query);
            
            //Set protocol
            $protocol = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';

            //Set GET and redirect to the index page
            header('Location: ' . $protocol . $_SERVER['SERVER_NAME'] . '?q=' . $search_query);
        }
        //If there is nothing in the Search field, do nothing
        else {
            return;
        }
    }
}