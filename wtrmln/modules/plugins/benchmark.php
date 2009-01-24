<?php
/********************************************************************

  Watermelon CMS

Copyright 2009 Radosław Pietruszewski

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
 * class Benchmark
 * 
 * sprawdzanie wydajności (w sensie: szybkości działania)
 */

class Benchmark extends Plugin
{
   /*
    * public static string[] $benchmarks
    * 
    * tablica zawierająca czasy (zapisywane tutaj przez Benchmark::start()).
    * czasy te są przedstawione jako string, ponieważ int przy skomplikowanych
    * aplikacjach mógłby nie zmieścić dużej liczby.
    * 
    * string[] $benchmarks = array(int $microtime[, int $microtime[, ...]])
    */
   
   public static $benchmarks;
   
   /*
    * public static void start(string $benchmarkName)
    * 
    * tworzy benchmark (bez zapisu do bazy) o nazwie $benchmarkName
    * i zapisuje aktualny czas
    * 
    * string $benchmarkName - nazwa benchmarku
    */
   
   public static function start($benchmarkName)
   {
      $microtime = self::microtime();
      
      // zapisujemy w prywatnej właściwości klasy aktualny czas
      self::$benchmarks[$benchmarkName] = $microtime;
   }
   
   /*
    * public static string end(string $benchmarkName[, bool $save = true])
    * 
    * kończy benchmark (rozpoczęty przez Benchmark::start()) oraz
    * zwraca różnicę czasów (w mikrosekundach). Czas ten jest przedstawiony
    * jako string, ponieważ int przy skomplikowanych aplikacjach mógłby nie
    * zmieścić dużej liczby.
    * 
    * Jeśli $save === true, zapisuje różnicę czasów do bazy danych.
    * 
    * np. jeśli zostanie odpalony Benchmark::start('testowyBenchmark'), a
    * następnie po 2000µs zostanie odpalony Benchmark::end('testowyBenchmark')
    * to zostanie zwrócone 2000, a wynik zostanie zapisany w bazie danych
    * 
    * string $benchmarkName - nazwa benchmarku
    * bool   $save = true   - zapisać do bazy? (tak, jeśli true)
    */
   
   public static function end($benchmarkName, $save = true)
   {
      // pobieramy aktualny czas
      $microtime = self::microtime();
      
      // liczymy różnicę
      if(function_exists('bcsub'))
      {
         $difference = bcsub($microtime, self::$benchmarks[$benchmarkName]);
      }
      else
      {
         $difference = $microtime - self::$benchmarks[$benchmarkName];
      }
      
      // zapisujemy różnicę w bazie, jeśli $save === true
      if($save)
      {
         DB::query("INSERT INTO `__benchmark` (`name`, `value`) VALUES ('%1','%2')", $benchmarkName, $difference);
      }
      
      return $difference;
   }
   
   /*
    * public static string[] results(string $benchmarkName)
    * 
    * zwraca zapisane w bazie danych wyniki benchmarku o nazwie $benchmarkName
    * w postaci tablicy z czasami w mikrosekundach. Czasy te są przedstawione
    * jako string, ponieważ int przy skomplikowanych aplikacjach mógłby nie
    * zmieścić dużej liczby.
    * 
    * string[] return         = array(string $microtime[, string $microtime[, ...]])
    * string   $benchmarkName - nazwa benchmarku, którego czasy mają zostać zwrócone
    */
   
   public static function results($benchmarkName)
   {
      // pobieramy czasy
      $valuesResource = DB::query("SELECT `value` FROM `__benchmark` WHERE `name` = '%1'", $benchmarkName);
      
      // jeśli brak czasów w bazie
      if($valuesResource->num_rows() == 0)
      {
         return array();
      }
      
      // skoro są to tworzymy z nich tablicę
      while($value = $valuesResource->to_obj())
      {
         $values[] = $value->value;
      }
      
      return $values;
   }
   
   /*
    * private static string microtime()
    * 
    * zwraca aktualny czas (od uniksowej Epoki - 1 stycznia 1970, 00:00:00 GMT)
    * w mikrosekundach (16 znaków). Czas ten nie jest przedstawiony jako int, bo
    * zostałby przycięty (int ma zbyt małą "pojemność")
    */
   
   private static function microtime()
   {
      // pobieramy czas
      $microtime = microtime();
      
      // rozdzielamy sec i msec
      $microtime = explode(' ', $microtime);
      
      // wycinamy z msec dwa pierwsze znaki, tj. "0,"
      $msec = substr($microtime[0],2);
      
      $sec  = $microtime[1];
      $time = $sec . $msec;
      
      // wycinamy z czasu dwa ostatnie znaki, tj. "00"
      $time = substr($time, 0, -2);
      
      return $time;
   }
}

?>