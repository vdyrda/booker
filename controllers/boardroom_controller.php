<?php
include_once MODEL_PATH.'boardroom_model.php';

class BoardroomController extends DefaultController
{
    public $model = null;
    
    function __construct()
    {
        // get the id of the boardroom
        $id = (int) get_env('id', 1, 'get');
        if (($id < 1) || ($id > BOARDROOMS)) {
            $id = 1;
        }        
        $this->model = new BoardroomModel($id);
    }

    /**
     *  Action "View" for the boardroom
     *  Variables to be used in the "View" : 
     *  $html, $user, $calendar, $this, $this->model
     */
    public function view()
    {
        global $html;
        
       // get working year and month from the URL
        $y = (int) get_env('year',0,'get');
        $m = (int) get_env('month',0,'get');
        // set the year and the month to work with :
        $calendar = new Calendar($y, $m);
        
        $employee_objects = Employee::find_all();
        $this->model->employee_objects = $employee_objects;
        
        // make variables accessable globally by $html object
        $this->model->calendar = $calendar;
        $html->model = $this->model;
        
        $this->model->find_appointments_by_month($calendar->year, $calendar->month);
        include VIEW_PATH.'view_boardroom.php';
    }
    
    public function index()
    {
        $this->view();
    }
}