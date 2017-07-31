<?php
class BoardroomModel {
    public $boardroom;
    public $appointments;

    function __construct($boardroom_id) { 
        $this->boardroom = new Boardroom($boardroom_id);
    }   
    
    public function find_appointments_by_month($year, $month) {
        $this->appointments = Appointment::find_by_month($this->boardroom->id, $year, $month);
    }
}
