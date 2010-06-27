<?php
 //  
 //  db.php
 //  Watermelon CMS
 //  
 //  Copyright 2008-2010 Radosław Pietruszewski.
 //  
 //  This program is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  This program is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * DB library
 * 
 * communication with database
 * 
 */

class DB
{
   public static $queriesCounter = 0;       // number of executed queries
   public static $errorList      = array(); // array of encountered erorrs (strings). Works only in debug mode
   public static $queriesList    = array(); // array of executed queries (strings)
   
   private static $link;   // resource of database (returned by mysql_connect)
   private static $prefix; // prefix of tables' names
   
   /*
    * public void connect(string $host, string $user, string $pass, string $name, string $prefix)
    * 
    * Connects with $name database on $host server as user $user having $pass password with $prefix tables' prefix
    */
   public function connect($host, $user, $pass, $name, $prefix)
   {
      self::$link = @mysql_connect($host, $user, $pass);
      
      self::$prefix = $prefix;
      
      if(!self::$link)
      {
         trigger_error('DB: error 0 - nie można połączyć z bazą danych', E_USER_ERROR); // TODO: multilingual
      }
      
      if(!@mysql_select_db($name))
      {
         trigger_error('DB: error 1 - nie można wybrać bazy danych', E_USER_ERROR); // TODO: multilingual
      }
   }
   
   /*
    * public static DBresult query(string $query[, string $arg1[, string $arg2[, ...]]])
    * 
    * Executes database query
    * 
    * //Returns false on error
    *
    * QUERIES SYNTAX: 
    *
    * Type tables' names using double underscore prefix
    *
    * Mark all input data (those we type in apostrophes) as %(number), and pass its value in $arg(number)              //FIXTRANSLATE
    * All values passed in separate arguments are filtered by mysql_real_escape_string                                 //TODO: make it true
    *
    * For example:
    * 
    * DB::query("SELECT `id`, `password` FROM `__users` WHERE `nick` = '%1' AND `salt` = '%2'", 'radex', '86fcf28678ebe8a0');
    * 
    * will be interpreted (if DB::$prefix == 'wcms_') as:
    * 
    * "SELECT `id`, `password` FROM `wcms_users` WHERE `nick` = 'radex' AND `salt` = '86fcf28678ebe8a0'"
    */
   
   public static function query($query)
   {
      // replacing double underscore prefix in tables' names with tables' prefix
      
      $query = str_replace('`__', '`' . self::$prefix, $query);
      
      // replacing input data palceholders (%number) with their values (passed in arguments)
      
      $numargs = func_num_args();
      $arg_list = func_get_args();
      
      for($i = 1; $i < $numargs; $i++)
      {
         $query = str_replace('%' . $i, $arg_list[$i], $query);
      }
      
      // incrementing queries counter
      
      self::$queriesCounter++;
      
      // saving a query if debug mode is on
      
      if(defined('WM_DEBUG'))
      {
         self::$queriesList[] = $query;
      }
      
      // executing query
      
      $queryResult = mysql_query($query);
      
      if($queryResult)
      {
         return new DBresult($queryResult);
      }
      
      // on error:
      
      self::$errorList[] = mysql_error();
      
      //panic('Nieudane wykonanie zapytania. Błąd: ' . self::lastError()); // TODO: consider if it always should trigger fatal error on error
      
      return false;
   }

   /*
    * public static string lastError()
    * 
    * Returns last encountered error
    */

   public static function lastError()
   {
      return end(self::$errorList);
   }
   
   /*
    * public static int insert_id()
    * 
    * returns ID generated in last query (usually ID of lastly added record)
    */
   
   public static function insert_id()
   {
      return mysql_insert_id();
   }
   
   /*
    * public static string[] queriesList()
    * 
    * returns list of executed queries if debug mode is on,
    * otherwise returns false
    */
   
   public static function queriesList()
   {
      if(defined('WM_DEBUG'))
      {
         return self::$queriesList;
      }
      
      return false;
   }
}

//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//

class DBresult
{
   public $res; // query result resource (returned by DB::query)
   
   public function DBresult($res)
   {
      $this->res = $res;
   }
   
   /*
    * public int num_rows()
    * 
    * Returns number of rows in result
    */
   
   public function num_rows()
   {
      return mysql_num_rows($this->res);
   }
   
   /*
    * public object to_obj()
    * 
    * Returns data as an object
    */
   
   public function to_obj()
   {
      return mysql_fetch_object($this->res);
   }
   
   /*
    * public array to_array()
    * 
    * Returns data as an array
    */
   
   public function to_array()
   {
      return mysql_fetch_array($this->res);
   }
   
   /*
    * public bool exists()
    * 
    * Returns true if searched record exists, or false otherwise
    */
   
   public function exists()
   {
      return (mysql_num_rows($this->res) == 0) ? false : true;
   }
}

?>
