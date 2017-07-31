<?php
global $html;
global $session;
global $message;

// Set the page header
$header = get_element('welcome');
$content = "<h1>Employee Add</h1>\n";
$content .= output_message($message);

$content .= <<<FORM
<form action='' method='post' class='f-employee'>
    <div class='form-group'>
        <label>Enter new employee name (required)</label>
        <input class='form-control' type='text' placeholder='First name Last name' name='e_name' id='e_name' minlength=5 required>
    </div>
    <div class='form-group'>
        <label>Enter new employee email (required)</label>
        <input class='form-control' type='email' placeholder='Email' name='e_email' id='e_email' required>
    </div>
    <input type='submit' name="submit" value='Add' class='btn btn-default'>
</form>
FORM;

// Page output
$html->header = $header;
$html->content = $content;
include_once LAYOUT_PATH."layout_default.php";
