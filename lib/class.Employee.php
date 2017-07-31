<?php
class Employee extends Database_object {
    protected static $table_name = "employees";
    protected static $db_fields = ['id', 'name', 'email'];
    
    public $name;
    public $email;    
}
