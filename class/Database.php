<?php
  class Database{
    private static $host = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $database = "database";
    /* You can change connection strings here. */


    private static $connected = false;
    private static $connection;


    public function __construct(){
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


    public static function connected(){
      return self::$connected;
    }


    public static function secure($string){
      if(self::connected()){
        return @mysqli_real_escape_string(self::$connection, $string);
      } return false;
    }
    /* Returns a secure string for risky areas. */


    public static function execute(){
      if(self::connected()){
        $args = func_get_args();
        foreach ($args as $arg) {
          if(is_string($arg)){
            @mysqli_query(self::$connection,$arg);
          }
        }
        return true;
      } return false;
    }
    /* Only executes the query, good for heavy usage. */

    private static function returnCount(string $query){
      $result = @mysqli_query(self::$connection,$query);
      if($result){
        $count = mysqli_num_rows($result);
        return $count;
      } return 0;
    }
    public static function count(string $query){
      if(self::connected()){
        return self::returnCount($query);
      } return false;
    }
    /* Returns the count of rows; if not successful, returns 0. */


    private static function returnFetch(string $query){
      $result = @mysqli_query(self::$connection,$query);
      if($result){
        $row = @mysqli_fetch_array($result,MYSQLI_ASSOC);
        return $row;
      } return NULL;
    }
    public static function fetch(string $query, $key = null){
      if(self::connected()){
        if(!is_null($key)){
          return self::returnFetch($query)[$key];
        }
        return self::returnFetch($query);
      } return false;
    }
    /* Fetches only one row from query. */


    private static function returnSuccess(string $query){
      return @mysqli_query(self::$connection,$query);
    }
    public static function success(string $query){
      if(self::connected()){
        return self::returnSuccess($query);
      } return false;
    }
    /* Returns true if the query successfully executed. */


    private static function returnMultiple(string $query){
      $result = @mysqli_query(self::$connection,$query);
      if($result){
        return @mysqli_fetch_all($result,MYSQLI_ASSOC);
      } return [];
    }
    public static function multiple(string $query){
      if(self::connected()){
        return self::returnMultiple($query);
      } return false;
    }
    /* Fetches multiple rows from query and puts them into an array. */
  }
?>
