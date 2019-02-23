<?php
  class Query{
    public static function insert($table, $values = []){
      $tableValues = null;
      $insertValues = null;
      foreach ($values as $key => $value) {
        $tableValues .= "$key,";
        $insertValues .= "'$value',";
      }
      $tableValues = mb_substr($tableValues, 0, -1, "UTF-8");
      $insertValues = mb_substr($insertValues, 0, -1, "UTF-8");
      return "INSERT INTO $table ($tableValues) VALUES ($insertValues);";
    }

    public static function update($table, $values = [], $condition = null){
      $setValuePairs = null;
      foreach ($values as $key => $value) {
        $setValuePairs .= "$key = '$value',";
      }
      $setValuePairs = mb_substr($setValuePairs, 0, -1, "UTF-8");
      $updateCondition = (!is_null($condition)) ? $condition : "1 = 1";
      return "UPDATE $table SET $setValuePairs WHERE $updateCondition;";
    }

    public static function select($table, $values = [], $condition = null){
      $selectValues = (!empty($values)) ? null : "*";
      foreach ($values as $value) {
        $selectValues .= "$value,";
      }
      $selectValues = mb_substr($selectValues, 0, -1, "UTF-8");
      $selectCondition = (!is_null($condition)) ? $condition : "1 = 1";
      return "SELECT $selectValues FROM $table WHERE $selectCondition;";
    }

    public static function delete($table, $condition = null){
      $deleteCondition = (!is_null($condition)) ? $condition : "1 = 1";
      return "DELETE FROM $table WHERE $deleteCondition;";
    }
  }
?>
