<?php
class Appointment extends Database_object  {
    protected static $table_name = "appointments";
    protected static $db_fields = ['id', 'boardroom_id', 'employee_id', 'start', 'end', 'notes', 'reccuring_start'];
    protected static $db_attr = ['id'=>'int', 'boardroom_id'=>'int',  'employee_id'=>'int', 'start'=>'datetime', 'end'=>'datetime',  'notes'=>'char', 'reccuring_start'=>'datetime'];
    
    /**
     * Get all appointments for the month of the year 
     * @param int $boardroom_id 
     * @param int $year
     * @param int $month
     * @return array
     */
    public static function find_by_month($boardroom_id, $year, $month) {
        $month = (($month<10) ? "0".$month : $month );
        $start = "{$year}-{$month}-01";
        $sql = "SELECT * FROM ".static::$table_name." WHERE boardroom_id= ".escape_string($boardroom_id)." AND  start >= '{$start}' AND DATE(start) <= LAST_DAY('{$start}') ORDER BY start";
        $result_array = self::find_by_sql($sql);
        return !empty($result_array) ? $result_array : false;
    }
    
    /**
     * Check boardroom availability  for each event in the list $events[]['start', 'end'] 
     * @param int $boardroom_id
     * @param array  of arrays $appointments
     * @return boolean
     */
    public static function is_boardroom_available($boardroom_id, $appointments) {
        if (empty($appointments)) { return true; }
        $condition = "";
        foreach($appointments as $app) {
            if ($condition != "") {
                $condition .= " AND";
            }
            $start = is_int($app['start']) ? strftime(DB_DATATIME_FORMAT, $app['start']) : $app['start'];
            $end = is_int($app['end']) ? strftime(DB_DATATIME_FORMAT, $app['end']) : $app['end'];
            $condition .= " ( (start<='{$start}' AND end>'{$start}') OR (start<'{$end}' AND end>='{$end}') ) ";
            if (!empty($app['id'])) {
                $condition .= " AND (id != {$app['id']}) ";
            }
        }
        $sql = "SELECT id FROM ".static::$table_name." WHERE $condition AND boardroom_id={$boardroom_id} LIMIT 1;";
        $result_array = static::find_by_sql($sql);
        return empty($result_array) ? true : false;
    }
    
    /**
     *  Create records of appointments from the array 
     * @param array $apps
     * @return int
     */
    public static function createMultiple($apps) {
        global $db;
        
        $fields = array_intersect(array_keys(static::$db_attr), array_keys($apps[0]));
        $cond = '';
        foreach($apps as $app) {
            $cond .= ($cond == '' ?  '' : ', ') . '(';
            $v = '';
            foreach ($fields as $field) {
                if ($v!=='') {$v .= ', '; }
                switch (static::$db_attr[$field]) {
                    case 'char':
                    case 'string':
                        $attr = "'".$app[$field]."'";
                        break;
                    case 'datetime':
                        $attr = "'".strftime(DB_DATATIME_FORMAT, $app[$field])."'";
                        break;
                    case 'int':
                    default:
                        $attr = (int) $app[$field];
                }
                $v .= $attr;
            }
            $cond .= $v.')';
        }
        $sql = "INSERT INTO ".static::$table_name." (".implode(',', $fields).") VALUES ".$cond;
        $db->query($sql);
        return $db->affected_rows;
    }

    
    /**
     * Updates multiple records
     * @param array of objects $apps
     * @return int
     */
    public static function updateMultiple($apps) {
        global $db;
        $affected_rows = 0;
        foreach ($apps as $app) {
            $v = "";
            foreach (self::$db_attr as $field=>$typ) {
                $v .= (($v!=='') ? ", " : " ") . $field . " = " . escape_string(self::prepare_val($app->$field, $typ));
            }
            $sql = "UPDATE ".self::$table_name." SET {$v}, submitted=NOW() WHERE id=".$app->id;
            $db->query($sql);
            $affected_rows += $db->affected_rows;
        }
        return $affected_rows>0 ? $affected_rows : false;
    }

    public static function prepare_val($val, $typ) {
        //$val = escape_string($val);
        switch ($typ) {
            case 'char':
            case 'string':
                $output = "'".$val."'";
                break;
            case 'datetime':
                if (is_string($val)) { $val = strtotime($val); };
                $output = "'". strftime(DB_DATATIME_FORMAT, $val) ."'";
                break;
            case 'int':
            default:
                $output = (int) $val;
        }
        return $output;
    }
    
    protected static function instantiate($record) {
        $object = new static;
        foreach($record as $attribute=>$value){
            $object->$attribute = $value;
        }
        return $object;
    }
    
    /**
     *  Find all appointments with the reccuring_start = $start
     * @param string $start (time)
     * @return array
     */
    public static function find_reccuring($start=0) {
        $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE reccuring_start = '".escape_string($start)."' ORDER BY start");
        return !empty($result_array) ? $result_array : false;
    }
    
    public static function find_by_employee($id=0) {
        $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE employee_id = ".escape_string($id)." LIMIT 1");
        return !empty($result_array) ? $result_array : false;
    }
}
