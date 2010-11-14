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
 * class DB
 * 
 * communication with MySQL database
 */

include 'Result.php';
// include 'Record.php';
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
    * public static resource $link
    * 
    * Connection resource
    * 
    * Don't change
    */
   
   public static $link;
   
   /*
    * public static string $prefix
    * 
    * Table names prefix
    * 
    * Don't change
    */
   
   public static $prefix;
   
   /*******************************************************/
   
   /*
    * public static DBResult query([bool $pure, ]string $query[, string $arg1[, string $arg2[, ...]]])
    * 
    * Executes database query, and returns DBResult object as a result
    * 
    * bool $pure - if TRUE, args and '__' replacing is not performed- only "pure" query is made
    * 
    * On error: throws an exception, and (in debug mode) saves an error to self::$errorsArray
    * 
    * QUERIES SYNTAX:
    * 
    * Precede table names with double underscore
    * 
    * Mark all input data (those typed in apostrophes) as %(number), and pass their values in $arg(number)
    * All values passed in arguments are filtered by mysql_real_escape_string
    *
    * For example:
    * 
    * DB::query("SELECT `id`, `password` FROM `__users` WHERE `nick` = '%1' AND `salt` = '%2'", 'radex', '86fcf28678ebe8a0');
    * 
    * will be interpreted (assuming that table prefix is set to 'wcms_') as:
    * 
    * "SELECT `id`, `password` FROM `wcms_users` WHERE `nick` = 'radex' AND `salt` = '86fcf28678ebe8a0'"
    */
   
   public static function query()
   {
      // args
      
      $args = func_get_args();
      
      if($args[0] === true) // if $pure is specified
      {
         $pure  = true;
         $query = $args[1];
         
         array_shift($args);
         array_shift($args);
      }
      else
      {
         $query = $args[0];
         
         array_shift($args);
      }
      
      // args and '__' replacing
      
      if(!$pure)
      {
         // replacing '__' with tables prefix

         $query = str_replace('__', self::$prefix, $query);

         // replacing input data palceholders (%number) with their (escaped) values passed in arguments
         
         foreach($args as &$arg)
         {
            $arg = mysql_real_escape_string($arg, self::$link);
         }

         $query = self::replaceArgs($query, $args);
      }
      
      // saving a query if debug mode is on, and query is not made during unit testing
      
      if(defined('WM_Debug') && !UnitTester::$areTestsRunning)
      {
         self::$queriesArray[] = $query;
      }
      
      // executing query, and returning DBResult if everything is fine
      
      $queryResult = mysql_query($query, self::$link);
      
      if($queryResult)
      {
         return new DBResult($queryResult);
      }
      
      // on error - throwing an exception
      
      throw new WMException('Napotkano błąd podczas wykonywania zapytania do bazy danych: "' . mysql_error(self::$link) . '"', 'DB:queryError');
   }
   
   /*******************************************************/
   
   /*
    * public static DBRecord insert(string $table)
    * 
    * Returns object representing record to be added to $table
    * 
    * Note that it doesn't actually insert it
    * 
    * ---
    * 
    * public static int insert(string $table, array $fields)
    * 
    * Adds record to $table
    * 
    * array $fields - array with column names, and field values of record to be added
    * 
    * $fields = array($columnName => $value, ...)
    */
   
   /*
    * public static DBResult select(string $table)
    * public static DBResult select(string $table, int[] $ids)
    * public static DBRecord select(string $table, int $id)
    * 
    * Selects records from $table, and returns object representing them
    * 
    * For select(string): selects all records
    * For select(string, int[]): selects records with given ID-s
    * For select(string, int): selects only record with given ID
    * 
    * Note that select(string, int) returns fetched object (DBRecord), and others return result set (DBResult)
    */
   
   /*
    * public static void update(string $table, int $id, array $fields)
    * 
    * Updates $id record in $table
    * 
    * array $fields - array with column names, and values of fields to be updated
    * 
    * $fields = array($columnName => $value, ...)
    */
   
   /*
    * public static void delete(string $table, int $id)
    * public static void delete(string $table, int[] $ids)
    * 
    * Deletes $id / $ids record(s) in $table
    */
   
   /*******************************************************/
   
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
   
   /*******************************************************/
   
   /*
    * public static void connect()
    * 
    * Connects with database. You don't need to do it for yourself.
    */
   
   public static function connect($host, $name, $user, $pass, $prefix)
   {
      // returning, if connection was already established
      
      if(self::$link !== null)
      {
         return;
      }
      
      // establishing connection
      
      self::$link = @mysql_connect($host, $user, $pass);
      
      self::$prefix = $prefix;
      
      // on errors
      
      if(!self::$link)
      {
         throw new WMException('Nie mogę połączyć się z bazą danych (mysql_connect zwrócił błąd: ' . mysql_error(self::$link) . ')', 'DB:connectError');
      }
      
      if(!@mysql_select_db($name))
      {
         throw new WMException('Nie mogę połączyć się z bazą danych (mysql_select_db zwrócił błąd: ' . mysql_error(self::$link) . ')', 'DB:selectError');
      }
      
      // setting proper charset
      
      self::query("SET NAMES 'utf8'");
   }
   
   /*******************************************************/
   
   /*
    * public static mixed sqlValue(mixed $value)
    * 
    * Returns SQL representation of $value:
    *    if string:    adds apostrophes before and after escapes string
    *    if int/float: returns the same
    *    if bool:      converts to string
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
      else
      {
         return $value;
      }
   }
   
   /*
    * private static string replaceArgs(string $query, array $args)
    * 
    * Replaces %x in $query with contents of $args[x-1]
    */
   
   private static function replaceArgs($query, $args)
   {
      $argNumState = false; // TRUE when collecting numbers after '%' sign
      $argNumber   = '';    // arg number (figures after '%' sign)
      
      for($i = 0, $j = strlen($query); $i < $j; $i++)
      { 
         $char         = $query[$i];
         $prevChar     = $query[$i - 1];
         $prevPrevChar = $query[$i - 2];
         $nextChar     = $query[$i + 1];
         
         // if collecting arg number figures
         
         if($argNumState)
         {
            // if last char

            if($i == $j - 1)
            {
               $lastChar = true;
            }
            
            // if first figure; validation is already done
            
            if($argNumber == '')
            {
               $argNumber .= $char;
               
               if($lastChar)
               {
                  $query2 .= $args[(int) $argNumber - 1];
               }
               
               continue;
            }
            
            // if number
            
            if($char >= '0' && $char <= '9')
            {
               $argNumber .= $char;
               
               if($lastChar)
               {
                  $query2 .= $args[(int) $argNumber - 1];
               }
               
               continue;
            }
            else
            {
               $argNumState = false;
               $query2     .= $args[(int) $argNumber - 1];
               $argNumber   = '';
            }
         }
         
         // if current char is '%', previous char isn't '\' (or two previous chars are '\\'), and next char is a number
         
         if($char == '%' && ($prevChar != '\\' || $prevPrevChar == '\\') && $nextChar >= '1' && $nextChar <= '9')
         {
            $argNumState = true;
            continue;
         }
         
         // if just a text char
         
         $query2 .= $char;
      }
      
      //--
      
      return $query2;
   }
}