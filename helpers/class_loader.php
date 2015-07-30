<?php
defined('START') or die();

//Libraries
require_once(dirname(dirname(__FILE__)) . '/lib/Dice.php');
require_once(dirname(dirname(__FILE__)) . '/lib/Parsedown.php');

require_once(dirname(dirname(__FILE__)) . '/models/MDatabase.php');
require_once(dirname(dirname(__FILE__)) . '/admin/controllers/form_processor.php');
require_once(dirname(dirname(__FILE__)) . '/admin/models/MAddingEntry.php');
require_once(dirname(__FILE__) . '/error.php');
require_once(dirname(__FILE__) . '/logger.php');
require_once(dirname(dirname((__FILE__))) . '/views/VDisplayResources.php');
require_once(dirname(dirname(__FILE__)). '/controllers/CPagination.php');
require_once(dirname(dirname(__FILE__)). '/views/VPagination.php');
require_once(dirname(dirname((__FILE__))) . '/views/header.php');
require_once(dirname(dirname(__FILE__)). '/controllers/CDisplayResources.php');
require_once(dirname(dirname((__FILE__))) . '/views/VDisplayKeywords.php');
require_once(dirname(dirname(__FILE__)). '/controllers/CSearchFormProcessor.php');

