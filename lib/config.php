<?php
/* Don't allow direct access */
defined('START') or die();

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** The name of the MySQL database */
define('DB_NAME', 'video_tutorials');

/** MySQL database username **/
define('DB_USER', 'video_tutorials');

/** MySQL database password */
define('DB_PASSWORD', 'video_tutorials');

/** The table prefix */
//we could add a prefix later on
//define('TABLE_PREFIX', '');

/** true - for dev mode, false - for production */
define('DEV_MODE', true);

// setup

if (DEV_MODE == true) { 
	ini_set('display_errors', 1);
	error_reporting(E_ALL & ~E_NOTICE);
}