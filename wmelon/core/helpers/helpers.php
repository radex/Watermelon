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
 * mixed CallMethodQuietly(object &$object, string $methodName[, array $args])
 * 
 * Calls method $methodName on $object object with arguments from $args array without triggering warnings about insufficient number of arguments, and returns result returned by called method
 * 
 * object &$object     - object to call method on
 * string  $methodName - name of method to call
 * array   $args       - list of parameters to be passed to specified method
 * 
 * Note that this function passes NULL for all required, but not given in $args parameters
 */

function CallMethodQuietly(&$object, $methodName, $args = array())
{
   $reflection       = new ReflectionMethod(&$object, $methodName);
   $methodArgsNumber = $reflection->getNumberOfRequiredParameters();
   $args             = (is_array($args)) ? $args : array();
   
   // assigning NULL for missing parameters
   
   if(count($args) < $methodArgsNumber)
   {
      for($i = 0, $j = $methodArgsNumber - count($args); $i < $j; $i++)
      {
         $args[] = null;
      }
   }
   
   return call_user_func_array(array(&$object, $methodName), $args);
}

/*
 * string[] FilesForDirectory(string $dirPath)
 * 
 * Returns array with paths for every file in specified directory (if $recursive == true, also including files in subdirectories)
 * 
 * string $dirPath              - path for directory, in which search will be performed
 * bool   $recursive    = true  - whether returned list will include files in subdirectories
 * bool   $returnObject = false - whether to return an array of SplFileInfo objects instead of strings
 */

function FilesForDirectory($dirPath, $recursive = true, $returnObject = false)
{
   $iterator = new DirectoryIterator($dirPath);
   
   $files = array();
   
   foreach($iterator as $file)
   {
      if($file->isFile())
      {
         if($returnObject)
         {
            $files[] = $file->getFileInfo();
         }
         else
         {
            $files[] = $file->getPathname();
         }
      }
      elseif($file->isDir() && !$file->isDot() && $recursive === true)
      {
         $subdirFiles = FilesForDirectory($file->getPathname(), true, $returnObject);
         
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
 * string ToHTMLArgs(array/object $array)
 * 
 * Translates an array or an object into list of HTML/XML properties, e.g:
 * 
 * array('foo1' => 'bar1', 'foo2' => 'bar2')
 * 
 * will be translated into:
 * 
 * 'foo1="bar1" foo2="bar2"'
 */

function ToHTMLArgs($struct)
{
   $arguments = '';
   
   foreach($struct as $key => $var)
   {
      $arguments .= ' ' . $key . '="' . $var . '"';
   }
   
   $arguments = substr($arguments, 1);
   
   return $arguments;
}

/*
 * string SiteURI(string $urn)
 * 
 * Makes URI to given page of a website
 * 
 * string $urn - page, e.g: 'blog/foo/bar', or '' (URN to main page)
 */

function SiteURI($urn)
{
   return WM_SiteURL . $urn;
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
 * Redirects to $urn page of a website
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
 * string HashString(string $string[, string $algo])
 * 
 * Makes hash of $string
 * 
 * Hash is calculated using $algo algorithm.
 * If $algo is not given (which is usually true), default algorithm will be used
 * 
 * HashString uses $algo() if exists, or hash() otherwise.
 */

function HashString($string, $algo = null)
{
   if($algo === null)
   {
      $algo = 'sha1';
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