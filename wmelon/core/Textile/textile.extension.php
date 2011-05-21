<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010-2011 Radosław Pietruszewski.
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

/*
 * Textile extension
 */

include 'textile.php';

class Textile_Extension extends Extension
{
   private static $textile; // instance of Textile_Lib
   
   /*
    * public static string textile(string $textile)
    * 
    * Converts Textile markup to HTML
    */
   
   public static function textile($text)
   {
      return self::textile_string($text, false);
   }
   
   /*
    * public static string textileRestricted(string $textile)
    * 
    * Converts restricted Textile markup to HTML
    * 
    * Use for comments, forum posts and similar
    */
   
   public static function textileRestricted($text)
   {
      return self::textile_string($text, true);
   }
   
   /**************************************************************************/
   
   protected static function textile_string($text, $restricted)
   {
      // path
      
      $path = CachePath . 'textile';
      
      if($restricted)
      {
         $path .= '_restricted';
      }
      
      $path .= '/' . md5($text) . '.php';
      
      // fetching cached or generating HTML from Textile
      
      if(!file_exists($path) || defined('WM_Debug'))
      {
         // generating
         
         if($restricted)
         {
            $textiled = self::$textile->TextileRestricted($text);
         }
         else
         {
            $textiled = self::generateTextile($text);
         }
         
         // caching
         
         if(!defined('WM_Debug'))
         {
            file_put_contents($path, '<?php __halt_compiler()?>' . $textiled); // so that direct access is not possible
         }
      }
      else
      {
         $textiled = file_get_contents($path);
         $textiled = substr($textiled, 25);      // removing <?php __halt_compiler()? >
      }
      
      return $textiled;
   }
   
   /*
    * Does actual convertion from Textile markup to HTML
    */
   
   protected static function generateTextile($text)
   {
      // textile
      
      $text = self::$textile->TextileThis($text);
      
      // fixing relative links
      
      $text = preg_replace_callback('/href="([^"]+)"/', array(__CLASS__, 'fixLinks'), $text);
      
      return $text;
   }
   
   /*
    * (pseudo-)constructor
    */
   
   public static function init()
   {
      self::$textile = new Textile_Lib;
      
      // once every 1000 views, clearing Textile cache
      
      if(mt_rand(0, 1000) == 0)
      {
         $items1 = FilesForDirectory(CachePath . 'textile/');
         $items2 = FilesForDirectory(CachePath . 'textile_restricted/');
         
         foreach(array_merge($items1, $items2) as $path)
         {
            if(substr($path, -4) == '.php') // checking for extensions, so that .gitignore won't be deleted
            {
               unlink($path);
            }
         }
      }
   }
   
   /*
    * adds website base URL to links that does not start with protocol name (e.g. 'http://')
    */
   
   protected static function fixLinks($args)
   {
      list($href, $url) = $args;
      
      if(preg_match('/^[a-z]+:/i', $url) == 1)
      {
         return $href;
      }
      else
      {
         return 'href="' . SiteURL . $url . '"';
      }
   }
}

// Textile_Extension is used instead of just Textile, so that use of method textile() is possible

class Textile extends Textile_Extension{}