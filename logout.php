<?php
// Core initialization
require_once "init.php";

// Login checking
if ($session->is_logged_in()) { 
    $session->logout();
}

redirect_to("login.php"); 