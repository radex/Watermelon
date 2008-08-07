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


/* nagłówek
*******************************/

function setH1($value)
{
   define('WTRMLN_H1',$value);

	return '<h1>' . $value . '</h1>';
}

/* Zamiana tablicy na obiekt
********************************/

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

/* Zamiana obiektu na tablicę
********************************/

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

/* Zamiana tablicy lub obiektu na HTML-owskie/XML-owskie argumenty
********************************/

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

/* Alias funkcji arrayToHTMLArguments
********************************/

function objectToHTMLArguments($object)
{
   return arrayToHTMLArguments($object);
}

/* zwraca element tablicy $_POST
********************************/

function _POST($key)
{
   return $_POST[$key];
}

/* tworzy polską odmianę rzeczownika dla liczebnika
*******************************/

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

/* tworzy link do podstrony
**************************************/

function site_url($url)
{
   return WTRMLN_SITEURL . $url;
}

/* określenie IP kolesia
/*
/* funkcja pochodzi oryginalnie z: http://php.org.pl/artykuly/3/22
**************************************/

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

/* zwraca hash określony w configu
***********************************/

function strHash($string, $algo = NULL)
{
   if($algo === NULL)
   {
      $algo = Config::$hashAlgo;
      $algo = $algo[0];
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

?>
