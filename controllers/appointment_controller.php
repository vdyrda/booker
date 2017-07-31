<?php
include_once MODEL_PATH.'appointment_model.php';

class AppointmentController extends DefaultController
{
    public $model = null;
    public function __construct()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        
        $this->model = new AppointmentModel();
    }

    public function view($id = 0)
    {
        $model = $this->model;
        include VIEW_PATH.'view_appointment.php';
    }
    
    
    public function delete() {
        global $session; 
        
        $id = (int) get_env("id",0,'get'); 
        $all = get_env("all",0,'get'); // 0 or 1
        $start = strip_tags(get_env('start', '', 'get'));
        $end = strip_tags(get_env('end', '', 'get'));
        $message = "Error: The event {$start} - {$end} was not removed.";
        
        if ($id > 0) {
            $result = $this->model->delete($id, $all);
            if ($result) {
                $message = "The event {$start} - {$end} has been removed.";
            }
        }
        $session->message($message);
        $backlink = get_env('HTTP-REFERER', 'index.php', 'server');
        redirect_to($backlink);
    }
    
    public function add()
    {
        global $html;
        global $boardroom;
        $id = (int) get_env('room_id', 1, 'get');
        $boardroom = new Boardroom($id);
        
        $employee_objects = Employee::find_all();
        
        // make variables accessable globally by $html object
        $this->model->employee_objects = $employee_objects;
        $html->model = $this->model;
        
        $model = $this->model;
        include VIEW_PATH.'view_appointment.php';
    }
    
    public function save() {
        global $session;
        global $message;
        $error = "";
        
        $boardroom_id = (int) get_env("boardroom_id", 0);
        if ($boardroom_id<1 || $boardroom_id>BOARDROOMS) {
            $error .= "Boardroom # is wrong.";
        } else {
            $boardroom = new Boardroom($boardroom_id);
        }
        
        // Get appointment time 
        $year = (int) get_env("b_year",0);
        $month = (int) get_env("b_month",0);
        $day = (int) get_env("b_day",0);
        
        $start_hour = (int) get_env("b_start_hour",0);
        $start_minute = (int) get_env("b_start_minute",0);
        $start_ampm = get_env("b_start_ampm", '');
        
        $end_hour = (int) get_env("b_end_hour",0);
        $end_minute = (int) get_env("b_end_minute",0);
        $end_ampm = get_env("b_end_ampm", '');
        
        $start_time = strtotime("{$year}-{$month}-{$day} {$start_hour}:{$start_minute}:00 {$start_ampm}");
        $end_time  = strtotime("{$year}-{$month}-{$day} {$end_hour}:{$end_minute}:00 {$end_ampm}");
        
        // ERROR CHECK 1:  
        // Users shouldn’t be able to book a boardroom for a date that has already passed
         if ($start_time < time()) {
             $error .= "<br>Time can't be in the past.";
         }
        // ERROR CHECK 2: Start time should be < End time
        if ($start_time  >= $end_time) {
            $error .= "<br>Wrong time period.";
        }

        // ERROR CHECK 3: The boardroom must be available for the time specified 
        // Don't check if appointment time is wrong or boardroom isn't set
        if (empty($error)) {
            $available = $boardroom->is_available([['start'=>$start_time, 'end'=>$end_time]]);
            if (!$available) {
                $error .= "<br>The boardroom ".$boardroom->id." isn't available for the time specified.";
            }
        }
        // ERROR CHECK 4: Reccurence
        $reccuring = (int) get_env("b_reccuring", 0);
        if ($reccuring) {
            // 4.1 Users must enter a value in the “Duration” box.
            $r_frequency = (int) get_env('b_reccuring_period','');
            $r_duration = (int) get_env("b_duration", 0);
            //  If you choose "bi-weekly" and put in an odd number of weeks, the computer will round down
            if ($r_frequency == REPEAT_BIWEEKLY) {
                if ($r_duration % 2 == 1) { $r_duration--; }
            }
                
            if (!$r_duration) {
                $error .= "<br>No duration specified for a reccuring event.";
            } elseif ($r_freequency == REPEAT_MONTHLY && ($r_duration>12 || $r_duration < 0)) {
                $error .= "<br>Wrong duration specified for a monthly reccuring event.";
            } elseif (($r_freequency == REPEAT_WEEKLY || $r_frequency == REPEAT_BIWEEKLY) && ($r_duration>4 || $r_duration < 0)) {
                $error .= "<br>Wrong duration specified for a reccuring event.";
            } else {
                // 4.2 check availability for  all appropriate days, whether it is weekly, bi-weekly or monthly
                $event_dates = Calendar::get_repeated($start_time, $end_time, $r_frequency, $r_duration);
                if (!$boardroom->is_available($event_dates)) {
                    $error .= "<br>Boardroom isn't available for the reccuring appointments specified.";
                }
            }
         } else {
             // not reccuring
             $event_dates[] = ['start'=>$start_time, 'end'=>$end_time];
         }
         
         /* 
          *   Create appointment(s) records in DB /  Go back if errors 
          */
        if (empty($error)) {
            // add a meeting to the database 
            $values = [];
            foreach ($event_dates as $event) {
                $arr = ['boardroom_id'=>$boardroom_id, 'employee_id'=>(int) get_env('b_employee',0), 
                    'start'=>$event['start'], 'end'=>$event['end'], 'submitted'=>time(), 'notes'=>get_env('b_notes','')];
                if (count($event_dates)>1) {
                    $arr['reccuring_start'] = $start_time;
                }
                $values[] =  sanitize_array($arr);
            }
            $aff_rows = Appointment::createMultiple($values);
            $session->message("The event {$start_hour}:{$start_minute}-{$end_hour}:{$end_minute} has been added.\n<br>The text for this event is {$values[0]['notes']}");
            redirect_to('index.php?m=boardroom&a=index&room_id='.$boardroom_id);
        } else {
            // Back to the referer page if  error
            $message = $error;
            $action = get_env("action","");
            if (in_array($action, ['add','edit'])) {
                $this->$action();
                return true;
            } else {
                $backlink = get_env("HTTP_REFERER", "index.php", 'server');
                redirect_to($backlink);
            }
        }
        
    }
    
    public function update() {
        global $session; 
        $apps = [];
        $id = (int) get_env("id", 0);
        $boardroom_id = (int) get_env("boardroom_id", 0);
        $start_time = get_env("b_start_time",'');
        $end_time = get_env("b_end_time",'');
        $notes = get_env('b_notes','');
        $employee_id = (int) get_env('b_employee',0);
        $all = get_env("b_all",0); // 0 or 1

        $error = "";        
        if ($boardroom_id<1 || $boardroom_id>BOARDROOMS) {
            $error .= "Boardroom # is wrong.";
        } else {
            $boardroom = new Boardroom($boardroom_id);
            $start = date_parse($start_time);
            $new_start_time = $start['hour'].":".$start['minute'];
            $end = date_parse($end_time);
            $new_end_time = $end['hour'].":".$end['minute'];

            $app = Appointment::find_by_id($id);
            if ($app) {
                // Find all the occurances
                if ($all==1) {
                    $apps = Appointment::find_reccuring($app->start);
                } else {
                    $apps[0] = $app;
                }
                if (!empty($apps)) {
                    $new_periods = [];
                    $ind = 0;
                    foreach ($apps as $app) {
                        $new_periods[$ind]['id'] = $app->id;
                        $new_periods[$ind]['start'] = substr_replace($app->start, $new_start_time, 11, 5);
                        $app->start = $new_periods[$ind]['start'];
                        $new_periods[$ind]['end'] = substr_replace($app->end, $new_end_time, 11, 5);
                        $app->end = $new_periods[$ind]['end'];
                        $app->notes = $notes;
                        $app->employee_id = (int) $employee_id;
                        if (!empty($app->reccuring_start)) {
                            $app->reccuring_start = $new_periods[$ind]['start'];
                        }
                        $ind++;
                    }
                    $available = $boardroom->is_available($new_periods);
                    if ($available) {
                        $result = Appointment::updateMultiple($apps);
                        if ($result) {
                            $session->message("{$result} appointments were updated.");
                        } else {
                            $error .= "<br>No appointments updated.";
                        }
                    } else {
                        $error .= "<br>The boardroom ".$boardroom->id." isn't available for the time specified.";
                    }
                } else {
                    $error .= "<br>Error: The event record was not found.";
                }
            } else {
                $error = "Unknown event id";
            }
        }
        $session->message($error);
        redirect_to(get_env('HTTP_REFERER','index.php','server'));
    }
}

