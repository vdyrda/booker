<?php
global $html;
global $user;
global $session;
global $message;

// Set the page header
$header = get_element('welcome');
$content = "<h1>Boardroom Booker <a href='index.php?m=boardroom&a=index&id=".$boardroom->id."'>Boardroom ".$boardroom->id."</a></h1>\n";
$content .= output_message($message);
$content .= get_element('appointment_form');

// Page output
$html->header = $header;
$html->content = $content;
include_once LAYOUT_PATH."layout_default.php";
