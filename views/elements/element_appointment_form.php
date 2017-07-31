<?php
global $boardroom; 
global $html;

$b_employee = (int) get_env("b_employee",0);
$employee_options = "";
foreach ($html->model->employee_objects as $employee_object) {
    $val = $employee_object->id;
    $employee_options .= "<option value='{$val}'".($val == $b_employee ? " selected" : '').">".$employee_object->name."</option>";
}

$b_month = (int) get_env("b_month",0);
$month_options = "";
$months = Calendar::getMonths();
foreach ($months as $num=>$month) {
    if (b_month == num) {
        $selected = ($num == $b_month) ? " selected" : "";
    } else { // current month is selected 
        $selected = ($num == (int) strftime("%m")) ? " selected" : "";
    }
    $month_options .= "<option value='{$num}'{$selected}>{$month}</option>";
}

$b_day = (int) get_env("b_day",0);
$days_options = "";
for ($num=1; $num<=31; $num++) {
    if (b_day == $num) {
        $selected = ($num== $b_day) ? " selected" : "";
    } else { // current month is selected 
        $selected = ($num == (int) strftime("%d")) ? " selected" : "";
    }    
    $days_options .= "<option value='{$num}'{$selected}>".zero($num)."</option>";
}

$b_year = (int) get_env("b_year", (int) strftime("%Y"));
$years_options = "";
for ($num=strftime("%Y"); $num<=MAX_YEAR; $num++) {
    $years_options .= "<option value='{$num}'".($num == $b_year ? ' selected': '').">{$num}</option>";
}

$b_start_minute = (int) get_env('b_start_minute', 0);
$b_end_minute = (int) get_env('b_end_minute', 0);
$start_minute_options = "";
$end_minute_options = "";
for ($num=0; $num<60; $num++) {
    $start_minute_options .= "<option value='{$num}'".($b_start_minute==$num ? ' selected' : '').">".zero($num)."</option>";
    $end_minute_options .= "<option value='{$num}'".($b_end_minute==$num ? ' selected' : '').">".zero($num)."</option>";
}

$b_start_hour = (int) get_env('b_start_hour', 100);
$b_end_hour = (int) get_env('b_end_hour', 100);
$ampm_start = "";
$ampm_end = "";
$start_hours_options = "";
$end_hours_options = "";
if (TIME_FORMAT==12) {
    $b_start_ampm = get_env('b_start_ampm', '');
    $ampm_start = "<select name='b_start_ampm'><option value='am'".($b_start_ampm=='am' ? ' selected' : '').">AM</option><option value='pm'".($b_start_ampm=='pm' ? ' selected' : '').">PM</option></select>";
    $b_end_ampm = get_env('b_end_ampm', '');
    $ampm_end = "<select name='b_end_ampm'><option value='am'".($b_end_ampm=='am' ? ' selected' : '').">AM</option><option value='pm'".($b_end_ampm=='pm' ? ' selected' : '').">PM</option></select>";
    for ($i=1; $i<=12; $i++) {
        $start_hour_options .= "<option value='{$i}'".($b_start_hour == $i ? ' selected' : '').">".zero($i)."</option>";
        $end_hour_options .= "<option value='{$i}'".($b_end_hour == $i ? ' selected' : '').">".zero($i)."</option>";
    }
} else {
    for ($i=0; $i<=23; $i++) {
        $start_hour_options .= "<option value='{$i}'".($b_start_hour ==$i ? ' selected' : '').">".zero($i)."</option>";
        $end_hour_options .= "<option value='{$i}'".($b_end_hour ==$i ? ' selected' : '').">".zero($i)."</option>";
    }
}
$repeat = [REPEAT_WEEKLY, REPEAT_BIWEEKLY, REPEAT_MONTHLY];

$b_notes = get_env('b_notes','');

$b_reccuring = (int) get_env('b_reccuring',0);
$reccuring = " 
        <div class='radio'><label><input type='radio' name='b_reccuring' value='0'".($b_reccuring==0 ? ' checked' : '')."> no</label></div>
        <div class='radio'><label><input type='radio' name='b_reccuring' value='1'".($b_reccuring==1 ? ' checked' : '')."> yes</label></div>
";

$b_reccuring_period = (int) get_env('b_reccuring_period', 0);
$reccuring_period = "
        <div class='radio'><label><input type='radio' name='b_reccuring_period' value='{$repeat[0]}'".($b_reccuring_period == $repeat[0] ? ' checked' : '')."> weekly</label></div>
        <div class='radio'><label><input type='radio' name='b_reccuring_period' value='{$repeat[1]}'".($b_reccuring_period == $repeat[1] ? ' checked' : '')."> bi-weekly</label></div>
        <div class='radio'><label><input type='radio' name='b_reccuring_period' value='{$repeat[2]}'".($b_reccuring_period == $repeat[2] ? ' checked' : '')."> monthly</label></div>
";

$b_duration= get_env('b_duration', '');
        
$output = <<<HEREDOC
<form action='index.php?m=appointment&a=save' method='post' class="form-a">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="boardroom_id" value="{$boardroom->id}">
    <div class="form-group g_employee">
        <label for="b_employee">1. Booked for:</label>
        <select name="b_employee" id="b_employee">
            $employee_options
        </select>
    </div>
    <div class="form-group g_date">
        <label>2. I would like to book this meeting:</label>
        <select name="b_month">
                $month_options
        </select>
        <select name="b_day">
                $days_options
        </select>
        <select name="b_year">
                $years_options
        </select>
    </div>
    <div class="form-group">
        <label>3. Specify what the time and end of the meeting (This will be what people see on the calendar):</label>
        <div class='form-group g_period'>
            <select name="b_start_hour">
                $start_hour_options
            </select>
            <select name="b_start_minute">
                $start_minute_options
            </select>
            $ampm_start
        </div>
        <div class='form-group g_period'>
            <select name="b_end_hour">
                $end_hour_options
            </select>
            <select name="b_end_minute">
                $end_minute_options
            </select>
            $ampm_end
        </div>
    </div>
    <div class='form-group g_notes'>
            <label for="b_notes">4. Enter the specifics for the meeting. (This will be what people see when they click on an event link):</label>
            <textarea name="b_notes" id="b_notes" rows=5 cols=40 required>{$b_notes}</textarea>
    </div>
    <div class='form-group g_is_reccuring'>
        <label>5. Is this going to be a reccuring event?</label>
        $reccuring
    </div>
    <div class='form-group g_reccuring_period'>
        <label>6. If it is reccuring, specify weekly, bi-weekly, or monthly.</label>
        $reccuring_period
    </div>
    <div class='form-group g_reccuring_number'>
            <label>If weekly or bi-weekly, specify the number of weeks for it to keep reccuring. If monthly, specify the number of months. (If you choose "bi-weekly" and put in an odd number of weeks, the computer will round down.)</label>
            <div class="check">
                <input type="text" name="b_duration" value="{$b_duration}" size="5"> duration (max 4 weeks)
            </div>
    </div>
    <div class="form-group submit">
        <input type="submit" name="submit" value="Submit" class="btn btn-default">
    </div>
</form>
HEREDOC;
