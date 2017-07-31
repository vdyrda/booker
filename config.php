<?php
// Application behaviour constants
defined('BOARDROOMS') ? null : define("BOARDROOMS", 3);                             // boardrooms number
defined('TIME_FORMAT') ? null : define("TIME_FORMAT", 12);                          // 24 or 12-hours format
defined('WEEK_FIRST_DAY') ? null : define("WEEK_FIRST_DAY", "Sunday");      // First day of the week
defined('START_DATE') ? null : define('START_DATE', strtotime("1 February 2017"));  // Start date for the application
defined('MAX_YEAR') ? null : define('MAX_YEAR', 2030); // No appointments after that year are allowed
defined('DB_DATATIME_FORMAT') ? null : define('DB_DATATIME_FORMAT', '%Y-%m-%d %H:%M:%S');
defined('DEFAULT_CONTROLLER') ? null : define('DEFAULT_CONTROLLER', 'boardroom');

defined('REPEAT_WEEKLY') ? null : define('REPEAT_WEEKLY', 1);
defined('REPEAT_BIWEEKLY') ? null : define('REPEAT_BIWEEKLY', 2);
defined('REPEAT_MONTHLY') ? null : define('REPEAT_MONTHLY', 4);

// Database Connection
defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
defined('DB_USER')   ? null : define("DB_USER", "dbuser");
defined('DB_PASS')   ? null : define("DB_PASS", "QR9qo82Db0A91lK1");
defined('DB_NAME')   ? null : define("DB_NAME", "booker");

// Paths
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', DS);
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.'lib'.DS);
defined('VENDOR_PATH') ? null : define('VENDOR_PATH', SITE_ROOT.'vendor'.DS);
defined('CONTROLLER_PATH') ? null : define('CONTROLLER_PATH', SITE_ROOT.'controllers'.DS);
defined('MODEL_PATH') ? null : define('MODEL_PATH', SITE_ROOT.'models'.DS);
defined('VIEW_PATH') ? null : define('VIEW_PATH', SITE_ROOT.'views'.DS);
defined('LAYOUT_PATH') ? null : define('LAYOUT_PATH', VIEW_PATH.'layouts'.DS);
defined('ELEMENTS_PATH') ? null : define('ELEMENTS_PATH', VIEW_PATH.'elements'.DS);
defined("CSS_PATH") ? null : define("CSS_PATH", LAYOUT_PATH."css".DS);
defined("JS_PATH") ? null : define("JS_PATH", LAYOUT_PATH."js".DS);

// Security parameters
defined('SALT_LENGTH') ? null : define('SALT_LENGTH', 22);
defined('SALT_GENERATE_COST') ? null : define('SALT_GENERATE_COST', 11); // for PHP < 7.0
