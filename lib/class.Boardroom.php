<?php
class Boardroom  {
    public $id;
    
    function __construct($id = 1) {
        $id = (int) $id;
        if (($id <1) || ($id>BOARDROOMS)) {
            trigger_error('Failed to initilize boardroom.  Wrong id # ' .$id, E_USER_ERROR);
        }
        $this->id = $id;
    }
    
    /**
     * Check if the boardroom available for each event in the list $events[]['start', 'end'] 
     * @param array $events
     * @return boolean
     */
    public function is_available($events) {
        return Appointment::is_boardroom_available($this->id, $events);
    }
}