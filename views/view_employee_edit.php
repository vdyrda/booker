<?php
global $html;
global $session;
global $message;

// Set the page header
$header = get_element('welcome');
$content = "<h1>Employee Edit</h1>\n";
$content .= output_message($message);

$content .= <<<FORM
<form action='' method='post' class='f-employee'>
    <input type='hidden' name='id' id='id' value='{$employee->id}'>
    <div class='form-group'>
        <label>Enter new employee name (required)</label>
        <input class='form-control' type='text' placeholder='First name Last name' name='e_name' id='e_name' minlength=5 required value='{$employee->name}'>
    </div>
    <div class='form-group'>
        <label>Enter new employee email (required)</label>
        <input class='form-control' type='email' placeholder='Email' name='e_email' id='e_email' required value='{$employee->email}'>
    </div>
    <input type='submit' name="submit" value='Update' class='btn btn-default'>
</form>
FORM;

// Page output
$html->header = $header;
$html->content = $content;
include_once LAYOUT_PATH."layout_default.php";
