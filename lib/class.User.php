<?php
class User extends Person {
    
    protected static $table_name="users";
    protected static $db_fields = array('id', 'username', 'password', 'name');

    public $username;
    public $password;
	
    public static function authenticate($uname="", $upass="") {
        $username = escape_string($uname);
        $password = escape_string($upass);

        $result_array = self::find_by_username($username);
        if (!empty($result_array)) {
            $check = Password::verify($password, $result_array->password);
            if ($check) {
              return self::instantiate($result_array);  
            } 
        } 
        return false;
    }
    
    public static function find_by_username($name='') {
        $result_array = self::find_by_sql("SELECT * FROM ".static::$table_name." WHERE username='".escape_string($name)."' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
}
