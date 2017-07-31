<?php
include_once MODEL_PATH.'employee_model.php';

class EmployeeController extends DefaultController
{
    public $model = null;
    public function __construct()
    {
        $this->model = new EmployeeModel();
    }
    
    public function add()
    {
        global $session;
        
        if (get_env('submit')=='Add') {
            $name = get_env('e_name','');
            $email = get_env('e_email','');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Error: Invalid email format for user {$name}.";
            } else {
                $record = ['name'=>$name, 'email'=>$email];
                $new = Employee::add($record); 
                $result = $new->save();
                if ($result) {
                    $message = "User ".$new->name." has been added successfully.";
                } else {
                    $message = "Error: User ".$new->name." has not been added.";
                }
            }
            $session->message($message);
            redirect_to("index.php?m=employee&a=view");
        }
        
        $model = $this->model;
        include 'views/view_employee_add.php';
    }

    public function edit()
    {
        global $session;
        
        if (get_env('submit')=='Update') {
            // form is submitted 
            $id = (int) get_env('id',0);
            $name = get_env('e_name','');
            $email = get_env('e_email','');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Error: Invalid email format for user {$name}.";
            } else {
                $employ=Employee::find_by_id($id);
                if ($employ) {
                    $employ->name = $name;
                    $employ->email = $email;
                    $result = $employ->save();
                    if ($result) {
                        $message = "User ".$employ->name." has been updated successfully.";
                    } else {
                        $message = "Error: User ".$employ->name." has not been updated.";
                    }
                } else {
                    $message = "Error: No employees have been updated.";
                }
            }
            $session->message($message);
            redirect_to("index.php?m=employee&a=view");
        } else {
            // before form submitting
            $id = get_env('id', 0, 'get');
            if ($id) {
                $employee = Employee::find_by_id($id);
                $model = $this->model;
                include 'views/view_employee_edit.php';
            } else {
                redirect_to(get_env('HTTP_REFERER','index.php?m=employee&a=view','server'));
            }
        }
        
    }
    
    public function view()
    {
        $model = $this->model;
        include 'views/view_employee.php';
    }
    
    public function index()
    {
        $this->view();
    }
    
    public function remove()
    {
        global $session;
        
        $id = (int) get_env('id', 0, 'get');
        if ($id) {
            $who = Employee::find_by_id($id);
            if ($who) {
                $name = $who->name;
                If (Appointment::find_by_employee($id)) {
                    $error = "Can't delete employee {$name}. There are appointments of him.";
                } else {
                       $result = $who->delete();
                       if ($result) {
                           $message = "Employee {$name} was removed.";
                       } else {
                           $error = "Employee {$name} was not removed.";
                       }
                }
            } else {
                $error = "Error: Can't delete. Wrong employee id";
            }
        }
        
        if ($error) {
            $message = $error;
        }
        $session->message($message);
        redirect_to(get_env('HTTP_REFERER','index.php?m=employee&a=view','server'));
    }
    
}