<?php
class AppointmentModel {
    public static  $table_name = "appointments";
    
    public function delete($id, $all=false) {
        $app = Appointment::find_by_id($id);
        if ($app) {
            return $app->delete();            
        } else {
            return false;
        }
    }
    
}