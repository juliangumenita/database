<?php
  class Query{
    const WHERE = "WHERE";
    const GROUP = "GROUP BY";
    const HAVING = "HAVING";
    const ORDER = "ORDER BY";

    const SPACE = " ";
    const COMMA = ",";
    const EQUAL = "=";
    const END = ";";
    const NULL = "NULL";

    const ALL = "*";
    const EVERYWHERE = "1 == 1";

    public static function insert($table, $values){
      $table_values = null;
      $insert_values = null;

      foreach ($values as $key => $value) {
        $table_values .= "`$key`" . self::COMMA . self::SPACE;

        $insert_values .=
        self::value($value)
        . self::COMMA
        . self::SPACE;
      }

      $table_values = rtrim($table_values, self::COMMA . self::SPACE);
      $insert_values = rtrim($insert_values, self::COMMA . self::SPACE);

      return "INSERT INTO `$table` ($table_values) VALUES ($insert_values);";
    }

    public static function update($table, $values, $constructors){
      $set_values = null;

      foreach ($values as $key => $value) {
        $value = self::secure($value);

        $set_values .=
        "`$key`"
        . self::SPACE
        . self::EQUAL
        . self::SPACE
        . self::value($value)
        . self::COMMA
        . self::SPACE;
      }

      $set_values = rtrim($set_values, self::COMMA . self::SPACE);

      return "UPDATE `$table` SET $set_values" . self::construct($constructors);
    }

    public static function delete($table, $constructors){
      return "DELETE FROM `$table`" . self::construct($constructors);
    }

    public static function select($table, $values, $constructors = []){
      $select_values = null;

      if(empty($values)){
        $select_values = self::ALL;
      } else if(is_string($values)){
        $select_values = $values;
      } else {
        foreach ($values as $value) {
          if(strpos($value, " ") !== false){
            $select_values .= $value . self::COMMA . self::SPACE;
          } else {
            $select_values .= "`$value`" . self::COMMA . self::SPACE;
          }
        }
      }

      $select_values = rtrim($select_values, self::COMMA . self::SPACE);

      return "SELECT $select_values FROM `$table`" . self::construct($constructors);
    }

    private static function construct($constructors = []){
      if(empty($constructors)){
        return self::END;
      } else if(is_string($constructors)) {
        return $constructors . self::END;
      } else {
        $temp = null;
        foreach ($constructors as $constructor) {
          $temp .= $constructor;
        } return $temp . self::END;
      }
    }

    private static function secure($string){
      if(class_exists("Database")){
        return Database::secure($string);
      } return $string;
    }

    private static function value($value){
      if(is_numeric($value)){
        return $value;
      } else if(is_null($value)){
        return self::NULL;
      } else {
        $value = self::secure($value);
        $value = trim($value);
        return "'$value'";
      }
    }

    /* Constructors */

    public static function where(){
      $selectors = func_get_args();

      if(empty($selectors)){
        return
        self::SPACE
        . self::WHERE
        . self::SPACE
        . self::EVERYWHERE;
      } else {
        $temp = self::WHERE;
        foreach ($selectors as $selector) {
          $temp .= self::SPACE . $selector . self::COMMA . self::SPACE;
        }
        $temp = rtrim($temp, self::COMMA . self::SPACE);
      } return self::SPACE . $temp;
    }

    public static function group($keys){
      if(empty($keys)){
        return null;
      } else {
        $temp = self::GROUP;
        foreach ($keys as $key) {
          $temp .= self::SPACE . $key . self::COMMA . self::SPACE;
        }
        $temp = rtrim($temp, self::COMMA . self::SPACE);
      } return self::SPACE . $temp;
    }

    public static function having($selector = null){
      if(empty($selector)){
        return
        self::SPACE
        . self::HAVING
        . self::SPACE
        . self::EVERYWHERE;
      } else {
        $temp = self::HAVING . self::SPACE . $selector;
      } return self::SPACE . $temp;
    }

    public static function order($keys){
      if(empty($keys)){
        return null;
      } else {
        $temp = self::ORDER;
        foreach ($keys as $key) {
          $temp .= self::SPACE . $key . self::COMMA . self::SPACE;
        }
        $temp = rtrim($temp, self::COMMA . self::SPACE);
      } return self::SPACE . $temp;
    }

    /* Selectors */

    public static function is($key, $value){
      if(strpos($value, ".") !== false || is_numeric($value)) {
        return "`$key` = $value";
      } return "`$key` = '$value'";
    }
    public static function not($key, $value){
      if(strpos($value, ".") !== false || is_numeric($value)) {
        return "`$key` != $value";
      } return "`$key` != '$value'";
    }
    public static function bigger($key, $value){
      if(strpos($value, ".") !== false || is_numeric($value)) {
        return "`$key` > $value";
      } return "`$key` > '$value'";
    }
    public static function smaller($key, $value){
      if(strpos($value, ".") !== false || is_numeric($value)) {
        return "`$key` < $value";
      } return "`$key` < '$value'";
    }

  }
?>
