<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2009-2010 Radosław Pietruszewski.
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

class Benchmark
{
   /*
    * public static array $benchmarks
    * 
    * Array of times (saved here by Benchmark::start()) for benchmarks
    * 
    * Time is presented as 16-char string (10 for Unix timestamp, 6 for microseconds)
    * 
    * array $benchmarks = array
    *    (
    *       $benchmarkName => $time,
    *       $benchmarkName => $time,
    *       ...
    *    )
    */
   
   public static $benchmarks;
   
   /*
    * public static void start(string $benchmarkName)
    * 
    * Starts counting time for passed benchmark
    * 
    * Use Benchmark::end() to end counting and return time difference (and possibly save to database)
    */
   
   public static function start($benchmarkName)
   {
      self::$benchmarks[$benchmarkName] = self::microtime();
   }
   
   /*
    * public static string end(string $benchmarkName[, bool $saveToDatabase = true])
    * 
    * Ends counting time for given benchmark, and returns time difference (in microseconds)
    * 
    * Time is presented as 16-char string (10 for Unix timestamp, 6 for microseconds)
    * 
    * If $saveToDatabase is true (which is default), it will be saved to database
    */
   
   public static function end($benchmarkName, $saveToDatabase = true)
   {
      $now  = self::microtime();
      $then = self::$benchmarks[$benchmarkName];
      
      unset(self::$benchmarks[$benchmarkName]);
      
      $difference = (int) bcsub($now, $then);
      
      // saving difference in DB if $saveToDatabase = true
      
      if($saveToDatabase)
      {
         DB::query("INSERT INTO `__benchmark` (`name`, `value`) VALUES ('?','?')", $benchmarkName, $difference);
      }
      
      return $difference;
   }
   
   /*
    * public static string[] results(string $benchmarkName)
    * 
    * Returns saved in database results of passed benchmark in the shape of array with times in microseconds
    * 
    * Time is presented as 16-char string (10 for Unix timestamp, 6 for microseconds)
    */
   
   public static function results($benchmarkName)
   {
      $timesRes = DB::query("SELECT `value` FROM `__benchmark` WHERE `name` = '?'", $benchmarkName);
      
      while($time = $timesRes->fetch())
      {
         $times[] = $time->value;
      }
      
      return $times;
   }
   
   /*
    * public static string microtime()
    * 
    * Returns current time (since 1 January 1970) in microseconds.
    * 
    * Time is presented as 16-char string (10 for Unix timestamp, 6 for microseconds)
    */
   
   public static function microtime()
   {
      $microtime = microtime();
      $microtime = explode(' ', $microtime); // splitting seconds and microseconds
      $msec      = substr($microtime[0],2);  // removing "0," from beginning of microseconds string
      $sec       = $microtime[1];
      $time      = $sec . $msec;
      $time      = substr($time, 0, -2);     // removing "00" from end of time string
      
      return $time;
   }
   
   /*
    * public static int executionTime()
    * 
    * Returns Watermelon execution time (in microseconds)
    * 
    * You can show it (if debug mode) in footer
    */
   
   public static function executionTime()
   {
      // start time
      
      $microtime = WM_StartTime;
      $microtime = explode(' ', $microtime);
      $msec      = substr($microtime[0],2);
      $sec       = $microtime[1];
      $time      = $sec . $msec;
      $time      = substr($time, 0, -2);
      
      //--
      
      return self::microtime() - $time;
   }
}