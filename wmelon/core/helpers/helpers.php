<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2008-2011 Radosław Pietruszewski.
 //  
 //  Watermelon is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon. If not, see <http://www.gnu.org/licenses/>.
 //  

include 'language.php';

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
 * string SiteURL(string $page)
 * 
 * Returns URL for given page of the website
 * 
 * Normally, URL is produced for website itself when called in website itself and for ACP when called in ACP
 * 
 * When you precede $page with '#/', URL for website is always produced
 * When you precede $page with '%/', URL for ACP is always produced
 */

function SiteURL($page = '', $type = null)
{
   $prefix = substr($page, 0, 2);
   
   if($prefix == '#/')
   {
      return SiteURL . substr($page, 2);
   }
   elseif($prefix == '%/')
   {
      return AdminURL . substr($page, 2);
   }
   else
   {
      return CurrURL . $page;
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
   if(!empty($_SERVER['HTTP_CLIENT_IP']))
   {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
   }
   elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
   {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
   }
   else
   {
      $ip = $_SERVER['REMOTE_ADDR'];
   }
   
   return $ip;
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
   
   $html = str_replace('href="#/',   'href="'   . SiteURL, $html);
   $html = str_replace('action="#/', 'action="' . SiteURL, $html);
   
   $html = str_replace('href="$/',   'href="'   . CurrURL, $html);
   $html = str_replace('action="$/', 'action="' . CurrURL, $html);
   
   $html = str_replace('href="%/',   'href="'   . AdminURL, $html);
   $html = str_replace('action="%/', 'action="' . AdminURL, $html);
   
   // href/action=x (it's better not to, but PHPTAL sometimes doesn't generate "" in attributes)
   
   $html = str_replace('href=#/',   'href='   . SiteURL, $html);
   $html = str_replace('action=#/', 'action=' . SiteURL, $html);
   
   $html = str_replace('href=$/',   'href='   . CurrURL, $html);
   $html = str_replace('action=$/', 'action=' . CurrURL, $html);
   
   $html = str_replace('href=%/',   'href='   . AdminURL, $html);
   $html = str_replace('action=%/', 'action=' . AdminURL, $html);
   
   return $html;
}

/*
 * string generateURLName(string $title)
 * 
 * Produces URL-friendly name from $title
 * 
 * Strips illegal characters (such as space or '&')
 */

function generateURLName($title)
{
   $name = (string) $title;
   
   $name = str_replace(array('?', '#', '&', "'", '"', '.'), '', $name);
   $name = str_replace(':', ' -', $name);
   $name = str_replace(' ', '_', $name);
   
   return $name;
}