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

 todo:
 - refactorize
 - translate
 - make admin/ prefix for admin pages
 - ... (finish this todo)


 */

/*
 * Lib URL
 * wersja 1.8.5
 * 
 * Parsowanie URL-i etc.
 * 
 */

class URL
{
   /*
    * public static string[] $segments
    * 
    * segmenty URL-a (np. foo/bar == array('foo', 'bar'))
    * 
    * $segments = array($segment[, $segment[, ... ]])
    *   $segment - pojedynczy segment
    */
   public static $segments = array();
   
   /*
    * public static string $class
    * 
    * nazwa kontrolera do wykonania
    */
   
   static public $class = '';
   
   /*
    * public static string $method
    * 
    * nazwa funkcji składowej (metody) kontrolera do wykonania
    */
   
   public static $method = '';
   
   /*
    * public static string $message
    * 
    * nazwa wiadomości do wykonania (gdy pusta: null)
    */
   
   public static $message = null;
   
   /*
    * private static bool $inited
    * 
    * true,  jeśli biblioteka została już zainicjalizowana
    * false, jeśli nie
    */
   
   private static $inited = false;
   
   /*
    * public static bool $onesegment = false
    * 
    * true,  jeśli w URL-u jest tylko jeden segment
    * false, jeśli w URL-u jest więcej niż jeden segment
    */
   
   public static $onesegment = false;

   /*
    * public void URL(string $default)
    * 
    * Konstruktor. Inicjalizuje bibliotekę uzupełniając pole 'segments'
    * Zwraca false jeśli już była wcześniej zainicjalizowana
    * Wywala błąd krytyczny, jeśli nie podano argumentu $default
    * i nie była jeszcze zainicjalizowana.
    * 
    * string $default - nazwa domyślnego kontrolera
    * 
    */
   
   public function URL($default = null)
   {
      // jeśli klasa została już zainicjalizowana, nie kontynuujemy działania
      // tej funkcji składowej
      
      if(self::$inited === true)
      {
         return false;
      }
      
      // żeby klasa mogła funkcjonować poprawnie, nie można pozwolić na
      // nie podanie domyślnego kontrolera. Tutaj musi być to zrobione "ręcznie"
      // dlatego, że funkcja jest (jako konstruktor) wykonywana wielokrotnie w
      // kodzie, już po inicjalizacji biblioteki.
      
      if($default === null)
      {
         panic('Lib URL: 0');
      }
      
      // pobieramy dane o URL-u
      
      $URL  = (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO'));
      $URL2 = array();
      
      // parsujemy segmenty URL-a (ze względów bezpieczeństwa pozwalam
      // tylko na znaki 0-9A-Za-z+-._)
      
      foreach(explode('/', $URL) as $segment)
      {
         $segment2 = '';
         foreach(str_split($segment) as $char)
         {
            $char = ord($char); // char -> int
            
            // pozwalam tylko na znaki takie jak:
            
            // cyfry
            
            if($char >= 48 && $char <= 57)
            {
               $segment2 .= chr($char);
            }
            
            // duże litery
            
            if($char >= 65 && $char <= 90)
            {
               $segment2 .= chr($char);
            }
            
            // małe litery
            
            if($char >= 97 && $char <= 122)
            {
               $segment2 .= chr($char);
            }
            
            // plus (+)
            
            if($char == 43)
            {
               $segment2 .= chr($char);
            }
            
            // minus (-)
            
            if($char == 45)
            {
               $segment2 .= chr($char);
            }
            
            // kropka (.)
            
            if($char == 46)
            {
               $segment2 .= chr($char);
            }
            
            // dwukropek (:)
            
            if($char == 58)
            {
               $segment2 .= chr($char);
            }
            
            // podkreślenie (_)
            
            if($char == 95)
            {
               $segment2 .= chr($char);
            }
         }
         
         // jeśli ktoś zrobił wielokrotne slashe, wywalamy pusty segment
         if(!empty($segment2))
         {
            $URL2[] = $segment2;
         }
      }
      
      // sprawdzamy czy jest ustawiona wiadomość
      
      if(isset($URL2[0]))
      {
         if(substr($URL2[0], 0, 4) == 'msg:')
         {
            $message = substr($URL2[0], 4);
            array_shift($URL2);
            
            $seg   = strtolower($message);
            $seg   = str_split($seg);
            $seg_n = '';
            
            // filtruję nazwę (zostawiam tylko litery i znaki podkreślenia
            
            foreach($seg as $char)
            {
               $char = ord($char);
               
               if($char >= 97 && $char <= 122)
               {
                  $seg_n .= chr($char);
               }
               
               if($char == 95)
               {
                  $seg_n .= chr($char);
               }
            }
            
            self::$message = $seg_n;
         }
      }
      
      // czyszczenie nazwy kontrolera
      // (jeśli brak nazwy kontrolera, ustaw na domyślną)
      
      
      if(isset($URL2[0]))
      {
         // czyszczenie segmentu z potencjalnie niebezpiecznego syfu
         // (nawet nie chodzi o bezpieczeństwo, ale wiadomo, że
         // kontroler o nazwie Foo.bar nie ma prawa istnieć
         // ze względu na ograniczenia nazewnictwa w PHP)
         
         $seg   = strtolower($URL2[0]);
         $seg   = str_split($seg);
         $seg_n = '';
         
         // parsujemy nazwę kontrolera (ze względów bezpieczeństwa pozwalam
         // tylko na znaki 0-9A-Za-z_)
         
         foreach($seg as $char)
         {
            $char = ord($char); //char -> int
            
            // pozwalam tylko na znaki takie jak:
            
            // litery
            
            if($char >= 97 && $char <= 122)
            {
               $seg_n .= chr($char);
            }
            
            // cyfry
            
            if($char >= 48 && $char <= 57)
            {
               $seg_n .= chr($char);
            }
            
            // podkreślenie (_)
            
            if($char == 95)
            {
               $seg_n .= chr($char);
            }
            
            // myślnik (-)
            
            if($char == 45)
            {
               $seg_n .= chr($char);
            }
         }
         
         $t[0] = $seg_n;
      }
      else
      {
         // jeśli nie podano nazwy kontrolera, ustaw na domyślną
         
         $t[0] = $default;
      }
      
      // czyszczenie nazwy funkcji składowej/metody kontrolera
      // (jeśli brak, ustaw na index)
      
      if(isset($URL2[1]))
      {
         $seg   = strtolower($URL2[1]);
         $seg   = str_split($seg);
         $seg_n = '';
         
         // parsujemy nazwę kontrolera (ze względów bezpieczeństwa pozwalam
         // tylko na znaki 0-9A-Za-z_)
         
         foreach($seg as $char)
         {
            $char = ord($char); //char -> int
            
            // pozwalam tylko na znaki takie jak:
            
            // litery
            
            if($char >= 97 && $char <= 122)
            {
               $seg_n .= chr($char);
            }
            
            // cyfry
            
            if($char >= 48 && $char <= 57)
            {
               $seg_n .= chr($char);
            }
            
            // podkreślenie (_)
            
            if($char == 95)
            {
               $seg_n .= chr($char);
            }
         }
         
         // niestety nie da się stworzyć metody o nazwie new :/
         
         if($seg_n == 'new')
         {
            $seg_n = '_new';
         }
         
         $t[1] = $seg_n;
      }
      else
      {
         // jeśli nie podano nazwy funkcji składowej/metody kontrolera
         // ustaw na index (domyślna funkcja składowa)
         
         $t[1] = 'index';
         
         self::$onesegment = true;
      }
      
      // usuń dwa pierwsze segmenty (kontroler i jego funkcja składowa)
      // jeśli są tylko dwa segmenty lub mniej, oczyść tablicę
      // (wychodzi na to samo, ale PHP nie wywala błędu)
      
      if(count($URL2) > 2)
      {
         $URL2 = array_splice($URL2, -(count($URL2) - 2));
      }
      else
      {
         $URL2 = array();
      }
      
      // złączenie tablic - tej z oczyszczonymi lub domyślnymi nazwami kontrolera
      // i funkcji składowej/metody kontrolera z tą tablicą, która ma pozostałe
      // segmenty (lub nie ma, jeśli nie podano)
      
      $URL2 = array_merge($t, $URL2);
      
      // nadanie odpowiednich wartości
      
      self::$segments = $URL2;
      self::$class    = self::$segments[0];
      self::$method   = self::$segments[1];
      self::$inited   = true;
   }

   /*
    * public string segment(int $ID)
    * 
    * Zwraca treść danego [$ID] segmentu.
    * Segmenty są liczone od jeden
    * (a nie od zera, jak to jest w PHP-owskich tablicach)
    * 
    * Zwraca false w przypadku niepowodzenia
    * (tj. jeśli żądany segment nie istnieje)
    * 
    */

   public function segment($ID)
   {
      if(isset(self::$segments[$ID - 1]))
      {
         return self::$segments[$ID - 1];
      }
      else
      {
         return false;
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
}
?>
