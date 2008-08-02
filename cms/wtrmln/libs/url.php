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
 * Lib URL
 * wersja 1.4.4
 *
 * Parsowanie URLi etc.
 *
 */

class URL
{
   /*
    * public static array $segments
    *
    * segmenty URL-a (np. foo/bar == array('foo', 'bar'))
    *
    * $segments = array($segment[, $segment[, ... ]])
    *   $segment - pojedynczy segment
    */
   public static $segments = array();

   /*
    * public string $class
    *
    * nazwa kontrolera do wykonania
    */

   public $class = '';

   /*
    * public string $method
    *
    * nazwa funkcji składowej (metody) kontrolera do wykonania
    */

   public $method = '';

   /*
    * private static URL $instance
    *
    * Zawiera instancję tej klasy
    */

   private static $instance = NULL;

   /*
    * public void URL([string $default = WTRMLN_DEFAULTCNT])
    *
    * string $default - jeśli nie ma nazwy controllera, jakiego wybrać?
    *                   jeśli nie podano argumentu, ustawiane jest na
    *                   WTRMLN_DEFAULTCNT
    *
    * Inicjuje bibliotekę uzupełniając pole 'segments'
    *
    * WAŻNE: Tą funkcję trzeba przepisać. Syf w niej jest i mało czytelna
    * (wcześniej też nie działała poprawnie, teraz jest ok)
    *
    */
   public function URL($default = WTRMLN_DEFAULTCNT)
   {
      // pobieram dane
      $URL  = (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');
      $URL2 = array();

      // parsuję człony

      foreach(explode('/',$URL) as $segment)
      {
         $segment2 = NULL;
         foreach(str_split($segment) as $ch)
         {
            $ch = ord($ch); // char -> int

            //pozwalam tylko na takie znaki:

            if($ch >= 48 && $ch <= 57)   // 0-9
               $segment2 .= chr($ch);
            if($ch >= 65 && $ch <= 90)   // A-Z
               $segment2 .= chr($ch);
            if($ch >= 97 && $ch <= 122)  // a-z
               $segment2 .= chr($ch);
            if($ch == 43)                // +
               $segment2 .= chr($ch);
            if($ch == 45)                // -
               $segment2 .= chr($ch);
            if($ch == 46)                // .
               $segment2 .= chr($ch);
            if($ch == 95)                // _
               $segment2 .= chr($ch);
         }
         // uważam, czy ktoś nie zrobił wielokrotnych slashów
         if(!empty($segment2))
            $URL2[] = $segment2;
      }


      // jeśli brak nazwy kontrolera
      // wczytaj domyślny.


      if(isset($URL2[0]))
      {
         //$t[0] = clean_segments($URL2[0]);

         //czyszczenie

         $seg = strtolower($URL2[0]); // małe literki
         $seg = str_split($seg);
         $seg_n = NULL;
         foreach($seg as $ch)
         {
            $ch = ord($ch);

            if($ch >= 97 && $ch <= 122)  // a-z, A-Z
               $seg_n .= chr($ch);
            if($ch >= 48 && $ch <= 57)   // 0-9
               $seg_n .= chr($ch);
            if($ch == 95)                // _
               $seg_n .= chr($ch);
         }
         $t[0] = $seg_n;
      }
      else
      {
         $t[0] = $default;
      }

      // jeśli brak nazwy metody
      // wczytaj index

      if(isset($URL2[1]))
      {
         //$t[1] = clean_segments($URL2[1]);

         //czyszczenie

         $seg_n = NULL;
         $seg = strtolower($URL2[1]);
         $seg = str_split($seg);
         foreach($seg as $ch)
         {
            $ch = ord($ch);
            if($ch >= 97 && $ch <= 122)  // a-z, A-Z
               $seg_n .= chr($ch);
            if($ch >= 48 && $ch <= 57)   // 0-9
               $seg_n .= chr($ch);
            if($ch == 95)                // _
               $seg_n .= chr($ch);
         }
         $t[1] = $seg_n;
      }
      else
      {
         $t[1] = 'index';
      }

      // jeśli wpisano więcej niż dwa człony
      // usuń dwa pierwsze, a jeśli nie
      // oczyść tablicę

      if(count($URL2) > 2)
      {
         $URL2 = array_splice($URL2, -(count($URL2)-2));
      }
      else
      {
         $URL2 = array();
      }

      // złączenie tablic

      $URL2 = array_merge($t,$URL2);

      self::$segments = $URL2;


      $this->class = self::$segments[0];
      $this->method = self::$segments[1];
   }

   /*
    * public mixed segment(int $ID)
    *
    * Zwraca treść danego [$ID] segmentu
    *
    */

   public function segment($ID)
   {
      if(isset(self::$segments[$ID-1]))
      {
         return self::$segments[$ID-1];
      }
      else
      {
         return FALSE;
      }
   }

   /*
    * public int segments()
    *
    * Zwraca ilość segmentów
    *
    */

   public function segments()
   {
      return count(self::$segments);
   }

   /*
    * public static object Instance()
    *
    * Singleton...
    *
    */
   public static function Instance()
   {
      if(!self::$instance instanceof self)
      self::$instance = new self;
      return self::$instance;
   }

}
?>
