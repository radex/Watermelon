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

//include 'gui.php';
include 'language.php';

include 'markup/textile.php';

/*
 * void SetH1(string $newHeader)
 * 
 * Sets header (name) of current subpage
 */

function SetH1($value)
{
   define('WM_H1', $newHeader);

	return '<h1>' . $newHeader . '</h1>';
}

/*
 * object ArrayToObject(array $array)
 * 
 * Translates $array into an object
 */

function ArrayToObject(array $array)
{
   foreach($array as $key => $var)
   {
      if(is_array($var))
      {
         $object->$key = ArrayToObject($var);
      }
      else
      {
         $object->$key = $var;
      }
   }
   
   return $object;
}

/*
 * array ObjectToArray(object $object)
 * 
 * Translates $object into an array
 */

function ObjectToArray($object)
{
   foreach($object as $key => $var)
   {
      if(is_object($var))
      {
         $array[$key] = ObjectToArray($var);
      }
      else
      {
         $array[$key] = $var;
      }
   }
   
   return $array;
}

/*
 * string ArrayToHTMLArguments(array $array)
 * string ObjectToHTMLArguments(object $object)
 * 
 * Translates an array or an object into list of HTML/XML properties, e.g:
 * 
 * array('foo1' => 'bar1', 'foo2' => 'bar2')
 * 
 * will be translated into:
 * 
 * 'foo1="bar1" foo2="bar2"'
 */

function ArrayToHTMLArguments($array)
{
   $arguments = '';
   
   foreach($array as $key => $var)
   {
      $arguments .= ' ' . $key . '="' . $var . '"';
   }
   
   $arguments = substr($arguments, 1);
   
   return $arguments;
}

function ObjectToHTMLArguments($object)
{
   return ArrayToHTMLArguments($object);
}

/*
 * string SiteURI(string $urn)
 * 
 * Makes URI to given subpage
 * 
 * string $urn - subpage, e.g: 'blog/foo/bar', or '' (URN to main page)
 */

function SiteURI($urn)
{
   return WM_SITEURL . $urn;
}

/*
 * void Redirect(string $uri)
 * 
 * Redirects browser to $uri
 */

function Redirect($uri)
{
   header('Location: ' . $uri);
   exit;
}

/*
 * void SiteRedirect(string $urn)
 * 
 * Redirects to $urn subpage
 * 
 * Equivalent of Redirect(SiteURI($urn))
 */

function SiteRedirect($urn)
{
   Redirect(SiteURI($urn));
}

/*
 * string ClientIP()
 * 
 * returns visitor's IP
 * 
 * function comes from: http://php.org.pl/artykuly/3/22 (dead link).
 * I don't know if it's original source.
 */

// TODO: Find some other function to do that (i'm not sure about this function license, and it uses ereg instead of preg)

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
 * string HashString(string $string[, string $algo])
 * 
 * Makes hash of $string
 * 
 * Hash is calculated using $algo algorithm.
 * If $algo is not given (which is usually true), algorithm specified in config file will be used.
 * 
 * HashString uses $algo function to hash the string if exists, or hash() otherwise.
 */

function HashString($string, $algo = null)
{
   if($algo === null)
   {
      $algo = Config::$hashAlgo;
   }
   
   if(function_exists($algo))
   {
      return $algo($string);
   }
   else
   {
      return hash($algo, $string);
   }
}

?>