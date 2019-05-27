<?php
class Database{

  /**
  * Default configuration.
  */
  private static $host = "localhost";
  private static $username = "root";
  private static $password = "";
  private static $database = "database";

  private static $connected = false;
  private static $connection;

  /**
  * Use other configuration by creating a new object.
  */
  public function __construct($host, $username, $password, $database){
    self::$host = $host;
    self::$username = $username;
    self::$password = $password;
    self::$database = $database;
  }

  /**
  * Connect to the database.
  */
  public static function init() {
    if(!isset(self::$connection)){
      self::$connection = @mysqli_connect(
        self::$host,
        self::$username,
        self::$password,
        self::$database
      );
      if(!mysqli_connect_errno(self::$connection)){
        mysqli_set_charset(self::$connection, "utf8");
        /* Sets the default charset to UTF8. */
        self::$connected = true;
      }
    }
  }

  /**
  * Check whether  the connection has been made or not.
  * @return boolean
  */
  public static function connected() {
    self::init();
    return self::$connected;
  }

  /**
  * Secures the data and return a string ready for the query.
  * @var data
  * @return string
  */
  public static function secure($data) {
    self::init();
    if(self::connected()){
      return @mysqli_real_escape_string(self::$connection, $data);
    }

    return false;
  }

  /**
  * Executes query, or many queries.
  * @return boolean
  */
  public static function execute() {
    self::init();
    if(self::connected()){
      $args = func_get_args();
      foreach ($args as $arg) {
        if(is_string($arg)){
          @mysqli_query(self::$connection, $arg);
        }
      } return true;
    } return false;
  }

  /**
  * Count rows.
  * If function 'mysqli_num_rows' exists, it'll be used.
  * @return int
  */
  public static function count(string $query) {
    self::init();
    if(self::connected()){
      return self::_count($query);
    } return false;
  }
  private static function _count(string $query) {
    self::init();
    $result = @mysqli_query(self::$connection, $query);
    if($result){
      if(function_exists("mysqli_num_rows")){
        return mysqli_num_rows($result);
      } else {
        $count = 0;
        while($row = mysqli_fetch_assoc($result)){
          $count++;
        }
        return $count;
      }
    } return 0;
  }

  /**
  * Executes the query and fetches the results.
  * @var query
  * @var key: if set, desired key is returned.
  * @return array/string/int
  */
  public static function fetch(string $query, $key = null) {
    self::init();
    if(self::connected()){
      if(!is_null($key)){
        return self::_fetch($query)[$key];
      } return self::_fetch($query);
    } return [];
  }
  private static function _fetch(string $query) {
    self::init();
    $result = @mysqli_query(self::$connection, $query);
    if($result){
      $row = @mysqli_fetch_array($result, MYSQLI_ASSOC);
      return $row;
    } return [];
  }

  /**
  * Check if the query has been successfully executed.
  * @return boolean
  */
  public static function success(string $query) {
    self::init();
    if(self::connected()){
      return self::_success($query);
    } return false;
  }
  private static function _success(string $query) {
    self::init();
    return @mysqli_query(self::$connection, $query);
  }

  /**
  * Returns multiple rows from the query.
  * @return array[array]
  */
  public static function multiple(string $query) {
    self::init();
    if(self::connected()){
      return self::_multiple($query);
    } return false;
  }
  private static function _multiple(string $query) {
    self::init();
    $result = @mysqli_query(self::$connection, $query);
    if($result){
      return @mysqli_fetch_all($result, MYSQLI_ASSOC);
    } return [];
  }

}
?>
