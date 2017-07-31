<?php
global $html;
global $session;
global $message;

// Set the page header
$header = get_element('welcome');
$content = "<h1>Employee List</h1>\n";
$content .= output_message($message);

$employees = Employee::find_all();
$list = "\n<table class='emp'>";
foreach ($employees as $e) {
    $list .= "\n\t<tr><td><a href='mailto:{$e->email}'>{$e->name}</a></td>";
    $list .= "<td><a href='index.php?m=employee&a=remove&id={$e->id}'>REMOVE</a></td>";
    $list .= "<td><a href='index.php?m=employee&a=edit&id={$e->id}'>EDIT</a></td></tr>";
}
$list .= "</table>\n";
$content .= $list;
$content .= "<div class='emp_actions'><a href='index.php?m=employee&a=add' class='btn btn-default'>Add a new employee</a></div>";

// Page output
$html->header = $header;
$html->content = $content;
include_once LAYOUT_PATH."layout_default.php";


