<?php
/**
 * MySQLi database; only one connection is allowed. 
 */
class Database {
  private $_connection;
  private static $_instance;
  
  /**
   * Get an instance of the Database.
   * @return Database 
   */
  public static function getInstance() {
    if (!self::$_instance) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  
  /**
   * Constructor.
   */
  public function __construct() {
    $this->_connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    // Error handling.
    if ($this->_connection->connect_error) {
        trigger_error('Failed to connect to MySQL: ' . $this->_connection->connect_error, E_USER_ERROR);
    }
  }
  
  /**
   * Empty clone magic method to prevent duplication. 
   */
  private function __clone() {}
  
  /**
   * Get the mysqli connection. 
   */
    public function getConnection() {
        return $this->_connection;
    }
  
}