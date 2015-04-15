<?php

require_once(dirname(dirname(__FILE__)) . '/models/database.php');
require_once(dirname(dirname(__FILE__)) . '/admin/controllers/form_processor.php');
require_once(dirname(dirname(__FILE__)) . '/admin/models/adding_entry.php');
require_once(dirname(__FILE__) . '/error.php');
//require_once(dirname(dirname(__FILE__)). '/admin/views/add_resource.php');

$Error = new Error();
$Database = new Database($Error);
$AddingEntry = new AddingEntry($Error);
$FormProcessor = new FormProcessor($Database, $AddingEntry, $Error);



/*class ClassLoader {
    public $Database;
    public $FormProcessor;
    public $AddingEntry;
    
    public function __construct() {
        //global $ClassLoader;
        require_once(dirname(dirname(__FILE__)) . '/models/database.php');
        $this->Database = $Database;
        require_once(dirname(dirname(__FILE__)) . '/admin/controllers/form_processor.php');
        $this->FormProcessor = new FormProcessor($this);
        require_once(dirname(dirname(__FILE__)) . '/admin/models/adding_entry.php');
        $this->AddingEntry = $AddingEntry;
    }
    
    public function load_Database() {
        require_once(dirname(dirname(__FILE__)) . '/models/database.php');
        $this->database = $Database;
        return $Database;
    }
    
    public function load_Error() {
        require_once(dirname(__FILE__) . '/error.php');
        return $Error;
    }
    
    public function load_FormProcessor() {
        global $ClassLoader;
        require_once(dirname(dirname(__FILE__)) . '/admin/controllers/form_processor.php');
        return $FormProcessor;
    }
    
    public function load_AddingEntry() {
        require_once(dirname(dirname(__FILE__)) . '/admin/models/adding_entry.php');
        return $AddingEntry;
    }
}

$ClassLoader = new ClassLoader;
//$Database = $ClassLoader->load_Database();
$Error = $ClassLoader->load_Error(); //instantiating might not be necessary here
//$AddingEntry = $ClassLoader->load_AddingEntry();
//$FormProcessor = $ClassLoader->load_FormProcessor();*/