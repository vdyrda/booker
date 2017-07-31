<?php
class Database_object {
    protected static $table_name = "";
    protected static $db_fields = [];
    public $id;

    // Common Database Methods
    public static function find_all() {
        return static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE 1");
    }

    public static function find_by_id($id=0) {
        $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id= ".escape_string($id)." LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_name($name='') {
        $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE name='".escape_string($name)."' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }
  
    public static function find_by_sql($sql="") {
        global $db;
        
        $result_set = $db->query($sql);        
        if ($result_set) {
            $object_array = [];
            while ($row = $result_set->fetch_array(MYSQLI_ASSOC)) {
                $object_array[] = static::instantiate($row);
            }
            return $object_array;
        } else {
            return null;
        }
    }
    
    public static function count_all() {
        global $db;
        
        $sql = "SELECT COUNT(*) FROM ".static::$table_name;
        $result_set = $db->query($sql);
        if ($result_set) {
            $row = $result_set->fetch_array(MYSQLI_ASSOC);
            return array_shift($row);
        } else {
            return null;
        }
    }

    public static function add($record) {
        return static::instantiate($record);
    }
    
    protected static function instantiate($record) {
        $object = new static;
        foreach($record as $attribute=>$value){
          if($object->has_attribute($attribute)) {
            $object->$attribute = $value;
          }
        }
        return $object;
    }

    private function has_attribute($attribute) {
        // returns true or false
        return array_key_exists($attribute, $this->attributes());
    }

    protected function attributes() { 
        // return an array of attribute names and their values
        $attributes = [];
        
        foreach(static::$db_fields as $field) {
            if(property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }

    protected function sanitized_attributes() {
        $clean_attributes = [];
        
        foreach($this->attributes() as $key => $value){
            $clean_attributes[$key] = escape_string($value);
        }
        return $clean_attributes;
    }

    public function save() {
        // A new record won't have an id yet.
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create() {
        global $db;
        
        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO ".static::$table_name." (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        if($db->query($sql)) {
            $this->id = $db->insert_id;
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        global $db;
        
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = [];
        foreach($attributes as $key => $value) {
          $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE ".static::$table_name." SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE id=". escape_string($this->id);
        $db->query($sql);
        return ($db->affected_rows == 1) ? true : false;
    }

    public function delete() {
        global $db;
        
        $sql = "DELETE FROM ".static::$table_name;
        $sql .= " WHERE id=". escape_string($this->id);
        $sql .= " LIMIT 1";
        
        $db->query($sql);
        return ($db->affected_rows == 1) ? true : false;
    }
  
}