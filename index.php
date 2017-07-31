<?php
require_once "init.php";

if (!$session->is_logged_in()) { redirect_to("login.php"); }

require_once CONTROLLER_PATH.'default_controller.php';

// get module and action 
$action = get_env('a', 'index', 'get');
$module = get_env('m','','get');
$controller_name = DEFAULT_CONTROLLER;

// controllers that are allowed 
$controllers = ['appointment', 'employee', 'boardroom'];

if (in_array($module , $controllers)) {
    $controller_name = $module;
} else {
    $controller_name = DEFAULT_CONTROLLER;
}

require_once CONTROLLER_PATH.$controller_name."_controller.php";
$c_name = ucfirst($controller_name)."Controller";
$controller = new $c_name();
$controller->run($action);
