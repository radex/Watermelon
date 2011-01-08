<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2011 Radosław Pietruszewski.
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

include 'language.php';

if(Watermelon::$appType == Watermelon::Admin)
{
   include 'ACPTable.php';
   include 'AdminQuick.php';
}

/*
 * mixed CallMethodQuietly(object &$object, string $methodName[, array $args])
 * 
 * Calls method $methodName on $object object with arguments from $args array without triggering warnings about insufficient number of arguments, and returns result returned by called method
 * 
 * object $object     - object to call method on
 * string $methodName - name of method to call
 * array  $args       - list of parameters to be passed to specified method
 * 
 * Note that this function passes NULL for all required, but not given in $args parameters
 */

function CallMethodQuietly($object, $methodName, $args = array())
{
   $reflection       = new ReflectionMethod($object, $methodName);
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

/*
 * array ToObject(object/array $structure)
 * 
 * Converts $structure to object (recursively)
 */

function ToObject($structure)
{
   return TranslateStructure($structure, 'object');
}

/*
 * array ToArray(object/array $structure)
 * 
 * Converts $structure to array (recursively)
 */

function ToArray($structure)
{
   return TranslateStructure($structure, 'array');
}

/*
 * mixed TranslateStructure(object/array $structure, string $toType)
 * 
 * Converts $structure to array or object (recursively)
 * 
 * string $toType - type of structure to convert $structure to; either 'array' or 'object'
 */

function TranslateStructure($structure, $toType)
{
   if(!is_array($structure) && !is_object($structure))
   {
      throw new WMException('$structure is neither array nor object', 'wrongType');
   }
   
   if($totype !== 'array' && $toType !== 'object')
   {
      throw new WMException('$toType is neither "array" nor "object"', 'badArgument');
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
 * string SiteURL(string $page[, enum $type])
 * 
 * Makes URI to given page of a website
 * 
 * Note that for admin control panel it's URI for ACP, not website itself
 * 
 * Specify $type if you specifically want URI for ACP/website itself
 * 
 * Alternative way of specifying type is to precede $page with '#/' (for website) or '%/' (for ACP)
 * 
 * string $urn  - page, e.g: 'blog/foo/bar', or '' (for main page)
 * enum   $type = {'site', 'admin'}
 *    For 'site', function will return URI for website itself
 *    For 'admin', URI for admin control panel is returned
 *    For anything else, URI for current app type is used
 */

function SiteURL($page = '', $type = null)
{
   // site
   
   if(substr($page, 0, 2) == '#/')
   {
      $type = 'site';
      $page = substr($page, 2);
   }
   
   // admin
   
   if(substr($page, 0, 2) == '%/')
   {
      $type = 'admin';
      $page = substr($page, 2);
   }
   
   // generating
   
   switch($type)
   {
      case 'admin':
         return WM_AdminURL . $page;
      break;
      
      case 'site':
         return WM_SiteURL . $page;
      break;
      
      default:
         return WM_CurrURL . $page;
      break;
   }
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
 * void SiteRedirect(string $urn, $type = null)
 * 
 * Redirects to $urn page of a website
 * 
 * Equivalent of Redirect(SiteURL($urn))
 * 
 * Glance at SiteURL() documentation for more details
 */

function SiteRedirect($urn = '', $type = null)
{
   Redirect(SiteURL($urn, $type));
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
 * If $algo is not given (which is usually true), sha1 will be used
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

/*
 * int[] IDs(string $idString)
 * 
 * Converts ID string (e.g. '3' or '5,6,7') to ID-s array
 * 
 * All non-numbers and duplicates are removed
 */

function IDs($idString)
{
   $idString = explode(',', $idString);
   
   foreach($idString as $id)
   {
      // non-numbers converted to int are equal zero
      
      if($id === '0' || (int) $id > 0)
      {
         $ids[] = (int) $id;
      }
   }
   
   return array_unique($ids);
}

/*
 * string QuestionBox(string $message, string $yesPage)
 * 
 * Composes and returns question box HTML
 * 
 * string $message - question (HTML) to ask
 * string $yesPage - page (e.g. 'blog/deleteSubmit/') to redirect a browser on, when 'yes' clicked
 */

function QuestionBox($message, $yesPage)
{
   $h .= '<div class="questionBox">' . $message . '<menu>';
   $h .= '<input type="button" value="Anuluj" onclick="history.back()" autofocus>';
   $h .= '<form action="' . SiteURL($yesPage) . '" method="post"><input type="submit" value="Tak"></div>';
   $h .= '</menu></div>';
   
   return $h;
}

/*
 * string SiteLinks(string $html)
 * 
 * Replaces made in simple manner links in HTML (in href="" and action="") to absolute URL-s:
 *    #/ for website base URL
 *    $/ for either site or ACP URL (depending on which is currently active)
 *    %/ for ACP base URL
 */

function SiteLinks($html)
{
   // href/action="x"
   
   $html = str_replace('href="#/',   'href="'   . WM_SiteURL, $html);
   $html = str_replace('action="#/', 'action="' . WM_SiteURL, $html);
   
   $html = str_replace('href="$/',   'href="'   . WM_CurrURL, $html);
   $html = str_replace('action="$/', 'action="' . WM_CurrURL, $html);
   
   $html = str_replace('href="%/',   'href="'   . WM_AdminURL, $html);
   $html = str_replace('action="%/', 'action="' . WM_AdminURL, $html);
   
   // href/action=x (it's better not to, but PHPTAL sometimes doesn't generate "" in attributes)
   
   $html = str_replace('href=#/',   'href='   . WM_SiteURL, $html);
   $html = str_replace('action=#/', 'action=' . WM_SiteURL, $html);
   
   $html = str_replace('href=$/',   'href='   . WM_CurrURL, $html);
   $html = str_replace('action=$/', 'action=' . WM_CurrURL, $html);
   
   $html = str_replace('href=%/',   'href='   . WM_AdminURL, $html);
   $html = str_replace('action=%/', 'action=' . WM_AdminURL, $html);
   
   return $html;
}