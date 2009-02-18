<?php
/********************************************************************

  Watermelon CMS

Copyright 2008-2009 Radosław Pietruszewski

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
 * Text Helpers
 * 
 * helpery związane z obróbką tekstu
 */

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
   $timestamp = intval($timestamp);
   
   // mniej niż minuta temu
   
   if($timestamp + 60 > time())
   {
      return 'przed chwilą';
   }
   
   // mniej niż godzina temu
   
   if($timestamp + 3600 > time())
   {
      $minutesAgo = (int) ((time() - $timestamp) / 60);
      return $minutesAgo . ' ' . generatePlFormOf($minutesAgo, 'minutę', 'minuty', 'minut') . ' temu';
   }
   
   // dane z timestampu
   
   list($day, $month, $year, $hour, $minute) = explode('.', date('j.m.Y.H.i', $timestamp));
   
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
   
   switch($month)
   {
      case 1:
         $month = 'stycznia';
         break;
      case 2:
         $month = 'lutego';
         break;
      case 3:
         $month = 'marca';
         break;
      case 4:
         $month = 'kwietnia';
         break;
      case 5:
         $month = 'maja';
         break;
      case 6:
         $month = 'czerwca';
         break;
      case 7:
         $month = 'lipca';
         break;
      case 8:
         $month = 'sierpnia';
         break;
      case 9:
         $month = 'września';
         break;
      case 10:
         $month = 'października';
         break;
      case 11:
         $month = 'listopada';
         break;
      case 12:
         $month = 'grudnia';
         break;
   }
   
   return $day . ' ' . $month . ' ' . $year . ' ' . $hour . ':' . $minute;
}

?>
