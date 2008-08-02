<?php if(!defined('WTRMLN_IS')) die;
/********************************************************************

  Watermelon CMS

Copyright 2008 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

/*
 * Lib DB
 * wersja 1.7.3
 *
 * Komunikacja z bazą danych
 *
 */

class DB
{
   /*
    * public static int $queriesCounter
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
    * private static DB $instance
    *
    * Zawiera instancję tej klasy
    */

   private static $instance = NULL;

   /*
    * private static string $prefix
    *
    * prefiks nazw tabel (np. tabela 'users' wraz z prefiksem 'wcms_' to 'wcms_users')
    */

   private static $prefix;

   /*
    * public void connect(string $host, string $user, string $pass, string $name, string $prefix)
    *
    * Łączy z bazą danych
    *
    */
   public function connect($host, $user, $pass, $name, $prefix)
   {
      self::$link = @mysql_connect($host, $user, $pass);

      self::$prefix = $prefix;

      if(!self::$link)
      {
         panic('Lib DB: 0');
      }

      $dbselect = @mysql_select_db($name);

      if(!$dbselect)
      {
         panic('Lib DB: 1');
      }
   }

   /*
    * public DBresult query(string $query)
    *
    * Zapytanie do bazy danych
    *
    * Zwraca FALSE w przypadku porażki
    *
    */

   public function query($query)
   {
      // jeśli jakaś nazwa tabeli w "panie kopniętym" zaczyna się od
      // podwójnego podkreślnika, zamienia na prefix

      $query = str_replace('`__', '`' . self::$prefix, $query);

      // poniżej eksperymentalny kod. Może zostanie, może nie :P

      $numargs = func_num_args();
      $arg_list = func_get_args();
      for($i = 1; $i < $numargs; $i++)
      {
         $query = str_replace('%' . $i, $arg_list[$i], $query);
      }

      // inkrementujemy licznik zapytań

      self::$queriesCounter++;

      $queryResult = mysql_query($query);

      if($queryResult)
      {
         return new DBresult($queryResult);
      }
      else
      {
         self::$errorList[] = mysql_error();
         return FALSE;
      }
   }

   /*
    * public string[] errorList()
    *
    * Zwraca zawartość listy błędów
    *
    */

   public function errorList()
   {
      return self::$errorList;
   }

   /*
    * public string lastError()
    *
    * Zwraca ostatni napotkany błąd
    *
    */

   public function lastError()
   {
      return end(self::$errorList);
   }

   /*
    * public int queries()
    *
    * Zwraca liczbę wykonanych zapytań
    *
    */

   public function queries()
   {
      return self::$queriesCounter;
   }

   /*
    * public static DB Instance()
    *
    * Singleton... (zwraca instancję tej klasy)
    *
    */
   public static function Instance()
   {
      if(!self::$instance instanceof self)
      self::$instance = new self;
      return self::$instance;
   }

}

############
############
############

class DBresult
{
   public $res;  // resource zwrócony przez DB::query

   /*
    * public void DBresult(resource $res)
    *
    * Ustawia $this->res
    *
    */
   public function DBresult($res)
   {
      $this->res = $res;
   }

   /*
    * public int num_rows()
    *
    * Zwraca ilość znalezionych wyników
    *
    */

   public function num_rows()
   {
      return mysql_num_rows($this->res);
   }

   /*
    * public object to_obj()
    *
    * Zwraca dane w postaci obiektu
    *
    */

   public function to_obj()
   {
      return mysql_fetch_object($this->res);
   }

   /*
    * public array to_array()
    *
    * Zwraca dane w postaci tablicy
    *
    */

   public function to_array()
   {
      return mysql_fetch_array($this->res);
   }
}

?>
