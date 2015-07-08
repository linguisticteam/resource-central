<?php
//Libraries
require_once(dirname(dirname(__FILE__)) . '/lib/Parsedown.php');

require_once(dirname(dirname(__FILE__)) . '/models/database.php');
require_once(dirname(dirname(__FILE__)) . '/admin/controllers/form_processor.php');
require_once(dirname(dirname(__FILE__)) . '/admin/models/adding_entry.php');
require_once(dirname(__FILE__) . '/error.php');
require_once(dirname(__FILE__) . '/logger.php');
require_once(dirname(dirname((__FILE__))) . '/views/VDisplayResources.php');
require_once(dirname(dirname(__FILE__)). '/controllers/controller_pagination.php');
require_once(dirname(dirname(__FILE__)). '/views/VPagination.php');
require_once(dirname(dirname((__FILE__))) . '/views/header.php');
require_once(dirname(dirname(__FILE__)). '/controllers/CDisplayResources.php');

//Libraries
$Parsedown = new Parsedown();

$Logger = new Logger();
$Error = new Error($Logger);
$Database = new Database($Error);
$AddingEntry = new AddingEntry($Error);
$FormProcessor = new FormProcessor($Database, $AddingEntry, $Error);

$VHeader = new VHeader();
$CPagination = new CPagination($Database);
$VPagination = new VPagination($CPagination);
$VDisplayResources = new VDisplayResources($Database, $CPagination, $Parsedown);
$CDisplayResources = new CDisplayResources($Database);


/* Previous approach
 * 
 * class ClassLoader {
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