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
- change host/user/pass/name/prefix passing to use Registry (when done)

*/

include 'DBResult.php';

class DB
{
   public static $queriesCounter = 0;       // number of executed queries
   public static $errorsArray    = array(); // array of encountered erorrs (strings). Works only in debug mode
   public static $queriesArray   = array(); // array of executed queries (strings)
   
   private static $link;   // resource of database (returned by mysql_connect)
   private static $prefix; // prefix of tables' names
   
   /*
    * public static void connect(string $host, string $user, string $pass, string $name, string $prefix)
    * 
    * Connects with $name database on $host server as $user having $pass password with $prefix tables' prefix
    * 
    * Returns TRUE if connection was established correctly
    * Returns FALSE if connection was already established
    * 
    * Throws an exception if connection was unsuccessful
    */
   
   public static function connect($host, $user, $pass, $name, $prefix)
   {
      if(self::$link !== null)
      {
         return false;
      }
      
      self::$link = @mysql_connect($host, $user, $pass);
      
      self::$prefix = $prefix;
      
      if(!self::$link)
      {
         throw new WMException('Nie mogę połączyć się z bazą danych (mysql_connect zwrócił błąd)', 'DB:connectError');
      }
      
      if(!@mysql_select_db($name))
      {
         throw new WMException('Nie mogę połączyć się z bazą danych (mysql_select_db zwrócił błąd)', 'DB:selectError');
      }
      
      return true;
   }
   
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
      
      $args = array_shift(func_get_args()); // shifting query off the beginning of array
      
      foreach($args as $arg)
      {
         $arg = mysql_real_escape_string($arg);
         
         $query = str_replace('%' . $i, $arg, $query);
      }
      
      // incrementing queries counter, and saving a query if debug mode is on
      
      self::$queriesCounter++;
      
      if(defined('WM_DEBUG'))
      {
         self::$queriesArray[] = $query;
      }
      
      // executing query, and returning DBResult if everything is fine
      
      $queryResult = mysql_query($query);
      
      if($queryResult)
      {
         return new DBResult($queryResult);
      }
      
      // on error: saving an error, and throwing an exception
      
      self::$errorsArray[] = mysql_error();
      
      throw new WMException('Napotkano błąd podczas wykonywania zapytania do bazy danych: "' . mysql_error() . '"', 'DB:queryError');
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
      return mysql_insert_id();
   }
}