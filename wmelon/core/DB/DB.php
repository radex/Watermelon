<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2008-2011 Radosław Pietruszewski.
 //  
 //  Watermelon is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * class DB
 * 
 * communication with MySQL database
 */

include 'Result.php';
include 'Query.php';

class DB
{
   /*
    * public static string[] $queriesArray
    * 
    * List of executed queries
    * 
    * Works only in debug mode
    */
   
   public static $queriesArray = array();
   
   /*
    * public static string $prefix
    * 
    * Table names prefix
    * 
    * Don't change
    */
   
   public static $prefix;
   
   /*
    * Whether connection with database has been already established
    */
   
   private static $connected;
   
   /**************************************************************************/
   
   /*
    * public static object select(string $table, int $id)
    * 
    * Returns $id record from $table
    * 
    * If record doesn't exist, FALSE is returned instead
    */
   
   /*
    * public static DBResult select(string $table, int[] $ids)
    * 
    * Returns $ids records from $table
    */
   
   /*
    * public static DBResult select(string $table, array($key => $value))
    * 
    * Returns all records from $table for which $key field equals $value
    */
   
   public static function select($table, $condition)
   {
      $query = DBQuery::select($table);
      $query = self::applyCondition($query, $condition);
      
      $result = $query->act();
      
      if(!is_array($condition))
      {
         // returning object if [int $id]
         
         return $result->fetch();
      }
      else
      {
         // returning DBResult otherwise
         
         return $result;
      }
   }
   
   /*
    * public static int insert(string $table, array/object $fields)
    * 
    * Adds record to $table, and returns its ID
    * 
    * array/object $fields - structure with column names, and field values of record to be added
    * 
    * $fields = array($columnName => $value, ...)
    */
   
   public static function insert($table, $fields)
   {
      return DBQuery::insert($table)->set($fields)->act();
   }
   
   /*
    * public static int update(string $table, int $id, array/object $fields)
    * public static int update(string $table, int[] $ids, array/object $fields)
    * 
    *    Updates $id / $ids record(s) in $table
    * 
    * public static int update(string $table, array($key => $value), array/object $fields)
    * 
    *    Updates records in $table for which $key field equals $value
    * 
    * 
    * array/object $fields - structure with column names, and values of fields to be updated
    * 
    * $fields = array($columnName => $value, ...)
    * 
    * Returns number of affected records
    */
   
   public static function update($table, $condition, $fields)
   {
      $query = DBQuery::update($table)->set($fields);
      $query = self::applyCondition($query, $condition);
      
      return $query->act();
   }
   
   /*
    * public static int delete(string $table, int $id)
    * public static int delete(string $table, int[] $ids)
    * 
    *    Deletes $id / $ids record(s) in $table
    * 
    * public static int delete(string $table, array($key => $value))
    * 
    *    Deletes records in $table for which $key field equals $value
    * 
    * 
    * Returns number of deleted records
    */
   
   public static function delete($table, $condition)
   {
      $query = DBQuery::delete($table);
      $query = self::applyCondition($query, $condition);
      
      return $query->act();
   }
   
   /**************************************************************************/
   
   /*
    * public static DBResult query(string $query[, string $arg1[, string $arg2[, ...]]])
    * 
    * Executes query to database, and returns DBResult object as a result
    * 
    * Throws an exception on error
    * 
    * QUERIES SYNTAX:
    * 
    * Precede table names with double underscore
    * 
    * Mark all input data (those typed in apostrophes) as ?, and pass their values in corresponding $argX
    * All values passed in arguments are filtered by mysql_real_escape_string
    *
    * For example:
    * 
    *    DB::query("SELECT id, password FROM __users WHERE nick = '?' AND salt = '?'", 'radex', '86fcf28678ebe8a0');
    * 
    * will be interpreted (assuming that table prefix is set to 'wcms_') as:
    * 
    *    "SELECT id, password FROM wcms_users WHERE nick = 'radex' AND salt = '86fcf28678ebe8a0'"
    */
   
   public static function query($query)
   {
      // args
      
      $args = func_get_args();
      
      array_shift($args);
      
      // replacing '__' with tables prefix
      
      $query = str_replace('__', self::$prefix, $query);
      
      // replacing input data palceholders ('?') with their (escaped) corresponding values, passed in args
      
      foreach($args as &$arg)
      {
         $arg = mysql_real_escape_string($arg);
      }
      
      $query = self::replaceArgs($query, $args);
      
      // executing query
      
      return self::pureQuery($query);
   }
   
   /*
    * public static DBResult pureQuery(string $query)
    * 
    * Executes query to database, and returns DBResult object as a result
    * 
    * Throws an exception on error
    * 
    * As opposed to DB::query() it doesn't change anything in query, only "pure" query is made
    */
   
   public static function pureQuery($query)
   {
      // saving a query if debug mode is on
      
      if(defined('WM_Debug'))
      {
         self::$queriesArray[] = $query;
      }
      
      // executing query, and returning DBResult if everything is fine
      
      $queryResult = mysql_query($query);
      
      if($queryResult)
      {
         return new DBResult($queryResult);
      }
      
      // on error - throwing an exception
      
      throw new WMException('Napotkano błąd podczas wykonywania zapytania do bazy danych: "' . mysql_error() . '"', 'DB:queryError');
   }
   
   /**************************************************************************/
   
   /*
    * public static int insertedID()
    * 
    * Returns ID generated in last query (usually ID of last added record)
    */
   
   public static function insertedID()
   {
      return mysql_insert_id();
   }
   
   /*
    * public static int affectedRows()
    * 
    * Returns number of rows affected in last query
    * 
    * See mysql_affected_rows() documentation for more details
    */
   
   public static function affectedRows()
   {
      return mysql_affected_rows();
   }
   
   /**************************************************************************/
   
   /*
    * public static void connect()
    * 
    * Connects with database. Done automatically.
    */
   
   public static function connect($host, $name, $user, $pass, $prefix)
   {
      if(self::$connected)
      {
         return;
      }
      
      // establishing connection
      
      $link = @mysql_connect($host, $user, $pass);
      
      // on errors
      
      if(!$link)
      {
         throw new WMException('Nie mogę połączyć się z bazą danych (mysql_connect zwrócił błąd: ' . mysql_error() . ')', 'DB:connectError');
      }
      
      if(!@mysql_select_db($name))
      {
         throw new WMException('Nie mogę połączyć się z bazą danych (mysql_select_db zwrócił błąd: ' . mysql_error() . ')', 'DB:selectError');
      }
      
      // settings
      
      self::query("SET NAMES 'utf8'");
      
      self::$connected = true;
      self::$prefix    = $prefix;
   }
   
   /**************************************************************************/
   
   /*
    * public static mixed sqlValue(mixed $value)
    * 
    * Returns SQL representation of $value:
    *    if string:    adds apostrophes and escapes string
    *    if int/float: returns the same
    *    if bool/null: converts to string
    * 
    * (method is for use of DB and DBQuery)
    */
   
   public static function sqlValue($value)
   {
      if(is_string($value))
      {
         return "'" . mysql_real_escape_string($value) . "'";
      }
      elseif(is_bool($value))
      {
         return $value ? 'true' : 'false';
      }
      elseif($value === null)
      {
         return 'null';
      }
      else
      {
         return (string) $value;
      }
   }
   
   /*
    * private static string replaceArgs(string $query, array $args)
    * 
    * Replaces '?' characters in $query with corresponding $args item values
    */
   
   private static function replaceArgs($query, $args)
   {
      $query = explode('?', $query);
      
      $newQuery = '';
      
      foreach($query as $queryPart)
      {
         $newQuery .= $queryPart;
         $newQuery .= array_shift($args);
      }
      
      return $newQuery;
   }
   
   /*
    * private static DBQuery applyCondition(DBQuery $query, $condition)
    * 
    * Applies $condition to $query
    * 
    * Used by ::select/update/delete()
    * 
    * $condition is one of following:
    *    int   $id               - single integer applies ->where('id', $id)
    *    int[] $ids              - array of integer applies ->where('id', 'in', $ids)
    *    array($key => $value)   - associative array with one element applies ->where($key, $value)
    */
   
   private static function applyCondition($query, $condition)
   {
      if(is_array($condition))
      {
         $keys = array_keys($condition);
         $firstKey = $keys[0];
         
         if(is_int($firstKey))
         {
            // int[] ids
            
            $ids = $condition;
            
            foreach($ids as &$id)
            {
               $id = (int) $id;
            }
            
            return $query->where('id', 'in', $ids);
         }
         else
         {
            // [key => val]
            
            foreach($condition as $key => $value); // a trick to get key name and value
            
            return $query->where($key, $value);
         }
      }
      else
      {
         // int id
         
         $id = $condition;
         
         return $query->where('id', (int) $id);
      }
   }
}