<?php
require_once "config.php";
require_once "functions.php";
$user = null;

$mysqli = Database::getInstance();
$db = $mysqli->getConnection();

$session = new Session();
$message = $session->message();

$html = new Html();