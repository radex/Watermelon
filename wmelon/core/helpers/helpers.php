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

/*
 * string[] FilesForDirectory(string $dirPath)
 * 
 * Returns array with paths for every file in specified directory (if $recursive == true, also including files in subdirectories)
 * 
 * string $dirPath          - path for directory, in which search will be performed
 * bool   $recursive = true - whether returned list will include files in subdirectories
 */

function FilesForDirectory($dirPath, $recursive = true)
{
   $iterator = new DirectoryIterator($dirPath);
   
   $files = array();
   
   foreach($iterator as $file)
   {
      if($file->isFile())
      {
         $files[] = $file->getPathname();
      }
      elseif($file->isDir() && !$file->isDot() && $recursive === true)
      {
         $subdirFiles = FilesForDirectory($file->getPathname(), $recursive);
         
         foreach($subdirFiles as $subdirFile)
         {
            $files[] = $subdirFile;
         }
      }
   }
   
   return $files;
}

function ToObject($structure)
{
   return TranslateStructure($structure, 'object');
}

function ToArray($structure)
{
   return TranslateStructure($structure, 'array');
}

/*
 * not done yet
 */

function TranslateStructure($structure, $toType)
{
   if(!is_array($structure) && !is_object($structure))
   {
      throw new WMException('$structure is neither array nor object', 'wrongType');
   }
   
   if($toType !== 'object' && $totype !== 'array')
   {
      throw new WMException('$toType is neither "object" nor "array"', 'badArgument');
   }
   
   if($toType == 'object')
   {
      $result = new stdClass;
   }
   else
   {
      $result = array();
   }
   
   foreach($structure as $key => $value)
   {
      if($toType == 'object')
      {
         $dest = &$result->$key;
      }
      else
      {
         $dest = &$result[$key];
      }
      
      if(is_array($value) || is_object($value))
      {
         $dest = TranslateStructure($value, $toType);
      }
      else
      {
         $dest = $value;
      }
   }
   
   return $result;
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
 * Returns visitor's IP address
 */

function ClientIP()
{
   $ip = false;
   
   if(!empty($_SERVER['HTTP_CLIENT_IP']))
   {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
   }
   elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
   {
      $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
      
      if($ip)
      {
         array_unshift($ips, $ip);
         $ip = false;
      }
      
      foreach($ips as $v)
      {
         if(!preg_match('#^(192\.168|172\.16|10|224|240|127|0)\.#', $v))
         {
            return $v;
         }
      }
   }
   else
   {
      $ip = $_SERVER['REMOTE_ADDR'];
   }
   
   return $ip;
}
/*
$obj = new stdClass;
$obj->a = false;
$obj->b = '1';
$array = array(1,2,3,'foo','bar',array(1,2,43, $obj));

var_dump(ObjectToArray(ArrayToObject($array)));
var_dump(ArrayToObject($array));
var_dump($array);*/

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