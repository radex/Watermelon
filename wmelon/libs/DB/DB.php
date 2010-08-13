<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2010 Radosław Pietruszewski.
 //  
 //  Watermelon CMS is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon CMS is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon CMS. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * DB library
 * 
 * communication with database
 * 
 */

/*

TODO:

- write test case, and generally test it
- perhaps some performance improvements (?)

*/

include 'DBResult.php';

class DB
{
   
   /*
    * public static string[] $errorsArray
    * 
    * List of encountered errors
    */
   
   public static $errorsArray = array();
   
   /*
    * public static string[] $queriesArray
    * 
    * List of executed queries
    * 
    * Works only in debug mode
    */
   
   public static $queriesArray = array();
   
   //---
   
   private static $link;   // resource of database (returned by mysql_connect)
   private static $prefix; // prefix of tables' names
   
   /*
    * public static DBresult query(string $query[, string $arg1[, string $arg2[, ...]]])
    * 
    * Executes database query, and returns DBResult object as a result
    * 
    * On error: throws an exception, and (in debug mode) saves an error to self::$errorsArray
    * 
    * QUERIES SYNTAX:
    * 
    * Type tables' names using double underscore prefix
    * 
    * Mark all input data (those we type in apostrophes) as %(number), and pass its value in $arg(number)
    * All values passed in separate arguments are filtered by mysql_real_escape_string, so don't do this for yourself
    *
    * For example:
    * 
    * DB::query("SELECT `id`, `password` FROM `__users` WHERE `nick` = '%1' AND `salt` = '%2'", 'radex', '86fcf28678ebe8a0');
    * 
    * will be interpreted (assuming that tables' prefix is set to 'wcms_') as:
    * 
    * "SELECT `id`, `password` FROM `wcms_users` WHERE `nick` = 'radex' AND `salt` = '86fcf28678ebe8a0'"
    */
   
   public static function query($query)
   {
      // replacing double underscore prefix in tables' names with tables' prefix
      
      $query = str_replace('`__', '`' . self::$prefix, $query);
      
      // replacing input data palceholders (%number) with their (filtered by mysql_real_escape_string) values passed in arguments
      
      $args = func_get_args();
      
      for($i = 1; $i < count($args); $i++) // i=1 to omit query
      {
         $arg = mysql_real_escape_string($args[$i]);
         
         $query = str_replace('%' . $i, $arg, $query);
      }
      
      // saving a query if debug mode is on
      
      if(defined('WM_DEBUG'))
      {
         self::$queriesArray[] = $query;
      }
      
      // executing query, and returning DBResult if everything is fine
      
      $queryResult = mysql_query($query, self::$link);
      
      if($queryResult)
      {
         return new DBResult($queryResult);
      }
      
      // on error: saving an error, and throwing an exception
      
      self::$errorsArray[] = mysql_error(self::$link);
      
      throw new WMException('Napotkano błąd podczas wykonywania zapytania do bazy danych: "' . mysql_error(self::$link) . '"', 'DB:queryError');
   }

   /*
    * public static string lastError()
    * 
    * Returns last encountered error, or FALSE if none was encountered
    */

   public static function lastError()
   {
      $errors = count(self::$errorsArray);
      
      if($errors > 0)
      {
         return self::$errorsArray[$errors - 1];
      }
      else
      {
         return false;
      }
   }
   
   /*
    * public static int insertedID()
    * 
    * Returns ID generated in last query (usually ID of last added record)
    */
   
   public static function insertedID()
   {
      return mysql_insert_id(self::$link);
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
      return mysql_affected_rows(self::$link);
   }
   
   /*
    * public static void connect()
    * 
    * Connects with database. You don't need to do it for yourself.
    * 
    * Returns TRUE if connection was established correctly
    * Returns FALSE if connection was already established
    * 
    * Throws an exception if connection was unsuccessful
    */
   
   public static function connect()
   {
      // return false, if connection was already established
      
      if(self::$link !== null)
      {
         return false;
      }
      
      // get database configuration from Registry
      
      $dbConfig = Registry::get('wmelon.db.config');
      
      Registry::invalidate('wmelon.db.config');
      
      // establish connection
      
      self::$link = @mysql_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['pass']);
      
      self::$prefix = $dbConfig['prefix'];
      
      // on errors
      
      if(!self::$link)
      {
         throw new WMException('Nie mogę połączyć się z bazą danych (mysql_connect zwrócił błąd: ' . mysql_error(self::$link) . ')', 'DB:connectError');
      }
      
      if(!@mysql_select_db($dbConfig['name']))
      {
         throw new WMException('Nie mogę połączyć się z bazą danych (mysql_select_db zwrócił błąd: ' . mysql_error(self::$link) . ')', 'DB:selectError');
      }
      
      //--
      
      return true;
   }
}