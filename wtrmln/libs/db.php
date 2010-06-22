<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2008-2009 Radosław Pietruszewski.
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
 * Lib DB
 * wersja 2.2.2
 * 
 * Komunikacja z bazą danych
 * 
 */

class DB
{
   /*
    * public static uint $queriesCounter
    * 
    * licznik zapytań (liczba wykonanych zapytań)
    */
   public static $queriesCounter = 0;

   /*
    * public static array $errorList
    * 
    * lista (log) błędów. Przydatne w debbugowaniu
    * 
    * $errorList = array(string $error[, string $error[, ...]])
    *   $error = treść błędu
    */

   public static $errorList = array();

   /*
    * private static mysql_link $link
    * 
    * resource bazy danych (zwracany przez mysql_connect)
    */

   private static $link;

   /*
    * private static string $prefix
    * 
    * prefiks nazw tabel (np. tabela 'users' wraz z prefiksem 'wcms_' to 'wcms_users')
    */

   private static $prefix;
   
   /*
    * private static string[] $queriesList
    * 
    * lista wykonanych zapytań
    */
   
   public static $queriesList = array();

   /*
    * public void connect(string $host, string $user, string $pass, string $name, string $prefix)
    * 
    * Łączy z bazą danych $name na serwerze $host jako user $user o haśle $pass z prefisami tabel $prefix
    */
   public function connect($host, $user, $pass, $name, $prefix)
   {
      self::$link = @mysql_connect($host, $user, $pass);

      self::$prefix = $prefix;

      if(!self::$link)
      {
         panic('Lib DB: error 0<br>nie można połączyć z bazą danych');
      }

      $dbselect = @mysql_select_db($name);

      if(!$dbselect)
      {
         panic('Lib DB: error 1<br>nie można wybrać bazy danych');
      }
   }

   /*
    * public static DBresult query(string $query[, string $arg1[, string $arg2[, ...]]])
    * 
    * Zapytanie do bazy danych
    * 
    * Nazwy tabel podajemy poprzedzając podwójnym podkreślnikeim
    * 
    * Wszystkie dane wejściowe (znaczy te, które podajemy w apostrofach) oznaczamy
    * jako %(cyfra), a w $arg(cyfra) podajemy zawartość tej danej
    * 
    * Zwraca FALSE w przypadku porażki
    * 
    * Przykład:
    * 
    * DB::query("SELECT `id`, `password` FROM `__users` WHERE `nick` = '%1' AND `salt` = '%2'", 'radex', '86fcf28678ebe8a0');
    * 
    * zostanie zinterpretowane (gdy DB::$prefix == 'wcms_') jako:
    * 
    * "SELECT `id`, `password` FROM `wcms_users` WHERE `nick` = 'radex' AND `salt` = '86fcf28678ebe8a0'"
    */

   public static function query($query)
   {
      // jeśli jakaś nazwa tabeli w "panie kopniętym" zaczyna się od
      // podwójnego podkreślnika, zamienia na prefix

      $query = str_replace('`__', '`' . self::$prefix, $query);

      // podmieniamy argumenty

      $numargs = func_num_args();
      $arg_list = func_get_args();
      
      for($i = 1; $i < $numargs; $i++)
      {
         $query = str_replace('%' . $i, $arg_list[$i], $query);
      }

      // inkrementujemy licznik zapytań

      self::$queriesCounter++;
      
      // zapisujemy zapytanie, jeśli tryb debug
      
      if(defined('DEBUG'))
      {
         self::$queriesList[] = $query;
      }
      
      // wykonujemy zapytanie

      $queryResult = mysql_query($query);

      if($queryResult)
      {
         return new DBresult($queryResult);
      }
      else
      {
         self::$errorList[] = mysql_error();
         
         panic('Nieudane wykonanie zapytania. Błąd: ' . self::lastError());
         
         return false;
      }
   }

   /*
    * public static string[] errorList()
    * 
    * Zwraca listę błędów
    */

   public static function errorList()
   {
      return self::$errorList;
   }

   /*
    * public static string lastError()
    * 
    * Zwraca ostatni napotkany błąd
    */

   public static function lastError()
   {
      return end(self::$errorList);
   }

   /*
    * public static uint queries()
    * 
    * Zwraca liczbę wykonanych zapytań
    */

   public static function queries()
   {
      return self::$queriesCounter;
   }
   
   /*
    * public static int insert_id()
    * 
    * zwraca ID ostatnio dodanego elementu
    */
   
   public static function insert_id()
   {
      return mysql_insert_id();
   }
   
   /*
    * public static string[] queriesList()
    * 
    * zwraca listę zapytań gdy włączony
    * tryb DEBUG, w przeciwnym wypadku
    * zwraca false
    */
   
   public static function queriesList()
   {
      if(defined('DEBUG'))
      {
         return self::$queriesList;
      }
      else
      {
         return false;
      }
   }
}

############
############
############

class DBresult
{
   /*
    * public mysql_result $res
    * 
    * resource zwrócony przez DB::query()
    */
   public $res;

   /*
    * public void DBresult(mysql_result $res)
    * 
    * Ustawia $this->res
    */
   public function DBresult($res)
   {
      $this->res = $res;
   }

   /*
    * public int num_rows()
    * 
    * Zwraca ilość znalezionych wyników
    */

   public function num_rows()
   {
      return mysql_num_rows($this->res);
   }

   /*
    * public object to_obj()
    * 
    * Zwraca dane w postaci obiektu
    */

   public function to_obj()
   {
      return mysql_fetch_object($this->res);
   }

   /*
    * public array to_array()
    * 
    * Zwraca dane w postaci tablicy
    */

   public function to_array()
   {
      return mysql_fetch_array($this->res);
   }
   
   /*
    * public bool exists()
    * 
    * Zwraca true, gdy element istnieje, false w przeciwnym wypadku
    */
   
   public function exists()
   {
      return (mysql_num_rows($this->res) == 0) ? false : true;
   }
}

?>
