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
 * Funkcje wspomagające związane z językiem
 */

/*
 * string PL_generateFormOf(int $numeral, string $inflection0, string $inflection1, $string $inflection2)
 * 
 * Tworzy odpowiednią polską odmianę rzeczownika dla danego liczebnika.
 * np. 0 arbuzów
 *     1 arbuz
 *     2 arbuzy
 * 
 * int $numeral - liczebnik, do którego ma zostać dopasowana odpowiednia forma rzeczownika
 * string $inflection0 - odmiana, taka jaka jest do liczby 0, np. 'arbuzów', 'rowerów'
 * string $inflection1 - odmiana, taka jaka jest do liczby 1, np. 'arbuz', 'rower'
 * string $inflection2 - odmiana, taka jaka jest do liczby 2, np. 'arbuzy', 'rowery'
 * 
 * przykład:
 * 
 * PL_generateFormOf(666, 'arbuzów', 'arbuz', 'arbuzy') -> 'arbuzów'
 */

function PL_generateFormOf($numeral, $inflection0, $inflection1, $inflection2)
{
   if($numeral == 0) return $inflection0;
   if($numeral == 1) return $inflection1;
   if($numeral > 1 && $numeral < 5)  return $inflection2;
   if($numeral > 4 && $numeral < 22) return $inflection0;
   
   $numeral = substr($numeral, -1, 1);

   if($numeral == 0 || $numeral == 1) return $inflection0;
   if($numeral > 1  && $numeral < 5)  return $inflection2;
   if($numeral > 4  && $numeral < 10) return $inflection0;
}

/*
 * string HumanDate(int $timestamp)
 * 
 * Tworzy prostą do zrozumienia datę z timestampu.
 * 
 * Jeżeli $timestamp był mniej niż minutę temu zwraca 'przed chwilą'
 * Jeżeli $timestamp był mniej niż godzinę temu zwraca 'x minut(ę/y) temu'
 * Jeżeli $timestamp to data z dzisiaj zwraca 'dzisiaj, hh:mm'
 * Jeżeli $timestamp to data z wczoraj zwraca 'wczoraj, hh:mm'
 * Jeżeli $timestamp to data z przedwczoraj zwraca 'przedwczoraj, hh:mm'
 * Jeżeli $timestamp był wcześniej niż przedwczoraj zwraca 'dd.mm.yyyy hh:mm' (PHP date(): d.m.Y H:i)
 * 
 * int $timestamp - Unix timestamp do przekształcenia w datę
 */
 
/*
 
TODO here:

- multilingual
- future dates
- sentences like "this monday", "last monday" or "next monday"
- HTML output where human-readable date is inside <span>, and there's number date (dd.mm.yyyy hh:mm) in its title as an option
- or other way round as an option
- date and time or date only (as an option)
- DMY/MDY/YMD to chose (in ACP)

*/

function HumanDate($timestamp)
{
   $timestamp = intval($timestamp);
   
   // less than a minute ago
   
   if($timestamp + 60 > time())
   {
      return 'przed chwilą';
   }
   
   // less than an hour ago
   
   if($timestamp + 3600 > time())
   {
      $minutesAgo = (int) ((time() - $timestamp) / 60);
      return $minutesAgo . ' ' . PL_generateFormOf($minutesAgo, 'minutę', 'minuty', 'minut') . ' temu';
   }
   
   // data from timestamp
   
   list($day, $month, $year, $hour, $minute) = explode('.', date('j.m.Y.H.i', $timestamp));
   
   // data from now
   
   list($dayN, $monthN, $yearN) = explode('.', date('d.m.Y', time()));
   
   // today, but more than an hour ago
   
   if($day == $dayN && $month == $monthN && $year == $yearN)
   {
      return 'dziś, ' . $hour . ':' . $minute;
   }
   
   // yesterday
   
   if($day == $dayN - 1 && $month == $monthN && $year == $yearN)
   {
      return 'wczoraj, ' . $hour . ':' . $minute;
   }
   
   // day before yesterday
   
   if($day == $dayN - 2 and $month == $monthN and $year == $yearN)
   {
      return 'przedwczoraj, ' . $hour . ':' . $minute;
   }
   
   // more than two days ago
   
   switch($month)
   {
      case 1:  $month = 'stycznia';     break;
      case 2:  $month = 'lutego';       break;
      case 3:  $month = 'marca';        break;
      case 4:  $month = 'kwietnia';     break;
      case 5:  $month = 'maja';         break;
      case 6:  $month = 'czerwca';      break;
      case 7:  $month = 'lipca';        break;
      case 8:  $month = 'sierpnia';     break;
      case 9:  $month = 'września';     break;
      case 10: $month = 'października'; break;
      case 11: $month = 'listopada';    break;
      case 12: $month = 'grudnia';      break;
   }
   
   return $day . ' ' . $month . ' ' . $year . ' ' . $hour . ':' . $minute;
}

?>
