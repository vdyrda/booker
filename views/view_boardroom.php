<?php
global $html;
global $user;
global $session;

$url_self = "index.php?m=boardroom&a=view";
// Set the page header
$header = get_element('welcome');
$header .= "<section id='rooms'>\n";
for ($i=1; $i<=BOARDROOMS; $i++) {
    $header .= ($i==1 ? "" : " | ") . "<a href='{$url_self}&id={$i}'>Boardroom {$i}</a>\n";
}
$header .= "</section>\n";
// Set the page content
$content = "<h1>Boardroom Booker <b>Boardroom ".$this->model->boardroom->id."</b></h1>";
$content .= output_message($session->message);
$content .= "<section id='month'>";

if (START_DATE < $calendar->month_start) {
    $content .= "<a href='{$url_self}&year={$calendar->prev_year}&month={$calendar->prev_month}'>&laquo;</a>";
}
$content .= "<span>".date("F Y", $calendar->month_start)."</span>";
$content .= "<a href='{$url_self}&year={$calendar->next_year}&month={$calendar->next_month}'>&raquo;</a>";
$content .= "</section>";

// get calendar table
$content .= "<div class='desk clear-both'>";
list($week_days, $tr, $table) = $calendar->getTable();

// appointments
$apps = [];
$appointments = $this->model->appointments;
if (!empty($appointments)) {
    foreach ($appointments as $app) {
        $day_of_month = (int) strftime('%d', strtotime($app->start));
        $apps[$day_of_month] []= $app;
    }
}

// Table filling 
$t = "\n\n<table id='board'>\n\t<thead>\n\t\t<tr>\n";
foreach ($week_days as $wd) {
    $t .= "<th>{$wd}</th>";
}
$t .= "\n\t\t</tr>\n\t</thead>\n\t<tbody>";
foreach ($tr as $td) {
    $t .= "\n\t\t<tr>";
    for ($i=0; $i<7; $i++) {
        $pp = '';
        if (!empty($td[$i])) {
            if (!empty($apps)) {
                $day_of_month = $td[$i];
                if (array_key_exists($day_of_month, $apps)) {
                    foreach ($apps[$day_of_month] as $app) {
                        $pp .= "<br><a href='' class='aroom' data-id='" . json_encode($app) . "' data-toggle='modal' data-target='#event'>" . substr($app->start, 11, 5) . " - " . substr($app->end, 11, 5) . "</a>" ;
                    }
                }
            }
        } 
        $t  .= "<td data='" . $td[$i] . "'><b>" . $td[$i] . "</b>{$pp}</td>";
    }
}
$t .= "</tr>\n\t</tbody>\n</table>\n\n";
$content .= $t;
$content .= "<aside id='board_side'><a href='index.php?m=appointment&a=add&room_id=".$this->model->boardroom->id."' class='btn btn-default'>Book It!</a>";
$content .= "<a href='index.php?m=employee&a=view' class='btn btn-default'>Employee List</a></aside>";
$content .= "</div>";

$b_employee = (int) get_env("b_employee",0);
$employee_options = "";
foreach ($html->model->employee_objects as $employee_object) {
    $val = $employee_object->id;
    $employee_options .= "<option value='{$val}'>".$employee_object->name."</option>";
}
$modal = <<<MODAL
            <!-- Modal -->        
            <div class="modal fade" id="event" tabindex="-1" role="dialog" aria-labelledby="eventLabel">
                <div class="modal-dialog modal-room" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Boardroom {$this->model->boardroom->id} Details</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-b" action="index.php?m=appointment&a=update" id="bb_details" method="post">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="boardroom_id" value="{$this->model->boardroom->id}">
                                <input type="hidden" name="id" id="id" value="">
                                <div class="form-group g_date">
                                    <label>When:</label>
                                    <input type="text" name="b_start_time" id="b_start_time" value="" required size="6">
                                    <span> - </span>
                                    <input type="text" name="b_end_time" id="b_end_time" value="" required size="6">
                                </div>
                                <div class='form-group g_notes'>
                                        <label for="b_notes">Notes:</label>
                                        <input type="text" name="b_notes" id="b_notes" required value="" size="30">
                                </div>
                                <div class="form-group g_employee">
                                    <label for="b_employee">Who:</label>
                                    <select name="b_employee" id="b_employee">
                                        $employee_options
                                    </select>
                                </div>
                                <p id="submitted"><strong>submitted:</strong> <span></span></p>
                                <div class='form-group g_all'>
                                    <label>Apply to all occurances?</label>
                                    <input type="checkbox" name="b_all" value="1" id="b_all">
                                </div>
                                <div class="form-group submit">
                                    <input type="submit" name="submit" value="Update" class="btn btn-primary">
                                    <a href="index.php?m=appointment&a=delete" id="delete" class="btn btn-danger">Delete</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
MODAL;
$content .= $modal;

// Page output
$html->header = $header;
$html->content = $content;
include_once LAYOUT_PATH."layout_default.php";
