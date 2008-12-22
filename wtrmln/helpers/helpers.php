<?php
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
 * void setH1(string $value)
 * 
 * ustawia nagłówek (nazwę) danej podstrony.
 * 
 * string $value - nazwa podstrony
 */

function setH1($value)
{
   define('WTRMLN_H1',$value);

	return '<h1>' . $value . '</h1>';
}

/*
 * object arrayToObject(array $array)
 * 
 * Zamienia tablicę na obiekt
 * 
 * array $array - tablica do zamiany na obiekt
 */

function arrayToObject(array $array)
{
   foreach($array as $key => $var)
   {
      if(is_array($var))
      {
         $object->$key = arrayToObject($var);
      }
      else
      {
         $object->$key = $var;
      }
   }
   
   return $object;
}

/*
 * array objectToArray(object $object)
 * 
 * Zamienia obiekt na tablicę
 * 
 * object $object - obiekt do zamiany na tablicę
 */

function objectToArray($object)
{
   foreach($object as $key => $var)
   {
      if(is_object($var))
      {
         $array[$key] = objectToArray($var);
      }
      else
      {
         $array[$key] = $var;
      }
   }
   
   return $array;
}

/*
 * string arrayToHTMLArguments(array $array)
 * string objectToHTMLArguments(object $object)
 * 
 * Zamiana tablicy lub obiektu na listę argumentów
 * HTML/XML, np.
 * 
 * array('foo1' => 'bar1', 'foo2' => 'bar2')
 * 
 * zostanie zamienione na:
 * 
 * 'foo1="bar1" foo2="bar2"'
 */

function arrayToHTMLArguments($array)
{
   $arguments = '';
   
   foreach($array as $key => $var)
   {
      $arguments .= ' ' . $key . '="' . $var . '"';
   }
   
   $arguments = substr($arguments, 1);
   
   return $arguments;
}

function objectToHTMLArguments($object)
{
   return arrayToHTMLArguments($object);
}

/*********************************************/

/* zwraca element tablicy $_POST
********************************/
/*
function _POST($key)
{
   return $_POST[$key];
}
*/

/**********************************************/

/*
 * string generatePlFormOf(int $int, string $odm1, string $odm2, $string $odm3)
 * 
 * Tworzy odpowiednią polską odmianę rzeczownika dla danego liczebnika.
 * np. 1 pies
 *     2 psy
 *     5 psów
 * 
 * int $int - liczebnik, do którego ma zostać dopasowana odpowiednia forma rzeczownika
 * string $odm1 - odmiana, taka jaka jest do liczby 1, np. 'pies', 'dom', 'rower'
 * string $odm2 - odmiana, taka jaka jest do liczby 2, np. 'psy', 'domy', 'rowery'
 * string $odm3 - odmiana, taka jaka jest do liczby 5, np. 'psów', 'domów', 'rowerów'
 * 
 * przykład:
 * 
 * generatePlFormOf(  1, 'rower', 'rowery', 'rowerów') -> 'rower'
 * generatePlFormOf( 52, 'arbuz', 'arbuzy', 'arbuzów') -> 'arbuzy'
 * generatePlFormOf(178, 'numer', 'numery', 'numerów') -> 'numerów'
 */

function generatePlFormOf($int, $odm1, $odm2, $odm3)
{
   if($int == 0) return $odm3;
   if($int == 1) return $odm1;
   if($int > 1 && $int < 5) return $odm2;
   if($int > 4 && $int < 22) return $odm3;
   $int = substr($int, -1, 1);

   if($int == 0 || $int == 1) return $odm3;
   if($int > 1 && $int < 5) return $odm2;
   if($int > 4 && $int < 10) return $odm3;
}

/*
 * string site_url(string $url)
 * 
 * Tworzy URL do danej podstrony
 * 
 * string $url - podstrona, np.: 'blog/foo/bar/', albo '' - pusty
 *               string, zwróci URL do strony głównej
 */

function site_url($url)
{
   return WTRMLN_SITEURL . $url;
}

/*
 * string ClientIP()
 * 
 * zwraca IP odwiedzającego.
 * 
 * funkcja pochodzi oryginalnie z: http://php.org.pl/artykuly/3/22
 */

function ClientIP()
{
   $ip = 0;

   if(!empty($_SERVER['HTTP_CLIENT_IP']))
   {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
   }

   if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
   {
      $ipList = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);

      if($ip)
      {
         array_unshift($ipList, $ip);
         $ip = 0;
      }

      foreach($ipList as $v)
      {
         if(!ereg('^(192\.168|172\.16|10|224|240|127|0)\.', $v))
         {
            return $v;
         }
      }
   }
   return $ip ? $ip : $_SERVER['REMOTE_ADDR'];
}

/*
 * string strHash(string $string[, string/int $algo])
 * 
 * tworzy hash z $string
 * 
 *  jeśli $algo nie zostało podane:
 *  
 *     tworzy hash według domyślnego algorytmu haszującego
 *  
 *  jeśli $algo jest stringiem
 *  
 *     tworzy hash według nazwy $algo
 *  
 *  jeśli $algo jest intem
 *  
 *     tworzy hash na podstawie numeru algrorytmu haszującego
 * 
 * string     $string - tekst do zahaszowania
 * string/int $algo   - nazwa lub numer algorytmu haszującego
 */

function strHash($string, $algo = NULL)
{
   if($algo === NULL)
   {
      $algo = Config::$hashAlgo;
      $algo = $algo[Config::$defaultHashAlgo];
   }
   elseif(is_int($algo))
   {
      $algo_id = $algo;
      $algo = Config::$hashAlgo;
      $algo = $algo[$algo_id];
   }

   $algoType = $algo[0];

   $algo = substr($algo, 1);

   // jeśli pierwszy znak to "x", używamy do haszowania funkcji hash. Jeśli
   // inny - używamy standardowej funkcji (obecnie są chyba tylko trzy,
   // może w PHP6 będzie więcej)

   if($algoType == 'x')
   {
      return hash($algo, $string);
   }
   else
   {
      return $algo($string);
   }
}

/*
 * string plDate(int $timestamp)
 * 
 * tworzy prostą do zrozumienia datę z timestampu
 * 
 * jeśli $timestamp był mniej niż minutę temu zwraca 'przed chwilą'
 * jeśli $timestamp był mniej niż godzinę temu zwraca 'x minut(ę/y) temu'
 * jeśli $timestamp to data z dzisiaj zwraca 'dzisiaj, hh:mm'
 * jeśli $timestamp to data z wczoraj zwraca 'wczoraj, hh:mm'
 * jeśli $timestamp to data z przedwczoraj zwraca 'przedwczoraj, hh:mm'
 * jeśli $timestamp był wcześniej niż przedwczoraj zwraca 'dd.mm.yyyy hh:mm'
 *                                                 [PHP date() -> d.m.Y H:i]
 * 
 * int $timestamp - Unix timestamp do przekształcenia w datę
 */

function plDate($timestamp)
{
   // mniej niż minuta temu
   
   if($timestamp + 60 > time())
   {
      return 'przed chwilą';
   }
   
   // mniej niż godzina temu
   
   if($timestamp + 3600 > time())
   {
      $minutesAgo = (int) ((time() - $timestamp)/60);
      return $minutesAgo . ' ' . generatePlFormOf($minutesAgo, 'minutę', 'minuty', 'minut') . ' temu';
   }
   
   // dane z timestampu
   
   list($day, $month, $year, $hour, $minute) = explode('.', date('d.m.Y.H.i', $timestamp));
   
   // dane z teraz
   
   list($dayN, $monthN, $yearN) = explode('.', date('d.m.Y', time()));
   
   // dziś, ale więcej niż godzinę temu
   
   if($day == $dayN and $month == $monthN and $year == $yearN)
   {
      return 'dziś, ' . $hour . ':' . $minute;
   }
   
   // wczoraj
   
   if($day == $dayN - 1 and $month == $monthN and $year == $yearN)
   {
      return 'wczoraj, ' . $hour . ':' . $minute;
   }
   
   // przedwczoraj
   
   if($day == $dayN - 2 and $month == $monthN and $year == $yearN)
   {
      return 'przedwczoraj, ' . $hour . ':' . $minute;
   }
   
   // dawniej niż przedwczoraj
   
   return $day . '.' . $month . '.' . $year . ' ' . $hour . ':' . $minute;
}

?>
