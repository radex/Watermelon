<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010 Radosław Pietruszewski.
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
 * Textile [A Humane Web Text Generator] extension
 */

/*
future ideas:

- make it extensible by other extensions
- and (as an example) make LaTeX extension (that'd be cool, huh?)
- make it cachable

*/

include 'textile.php';

class Textile_Extension extends Extension
{
   private static $textile;         // instance of Textile_Lib
   private static $shelf = array();
   
   /*
    * public static string textile(string $textile)
    * 
    * Converts Textile markup to HTML
    */
   
   public static function textile($text)
   {
      // shelving code snippets & PHP to be executed
      
      $text = preg_replace_callback('/<code\(([^)]+)\)>(.*?)<\/code>/ms', array(__CLASS__, 'shelveCode'), $text);
      $text = preg_replace_callback('/<exec>(.*?)<\/exec>/ms', array(__CLASS__, 'shelveExec'), $text);
      
      // textile
      
      $text = self::$textile->TextileThis($text);
      
      // unshelving
      
      foreach(self::$shelf as $i => $item)
      {
         $text = str_replace('<wm:shelf(' . $i . ')>', $item, $text);
      }
      
      return $text;
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
      return self::$textile->TextileRestricted($text);
   }
   
   /*
    * (pseudo-)constructor
    */
   
   public static function onAutoload()
   {
      self::$textile = new Textile_Lib;
   }
   
   /*
    * replaces <code()>..</code> syntax with placeholder (and puts HTML on "shelf"), so that entities won't be broken by Textile
    */
   
   private static function shelveCode($args)
   {
      list(, $brush, $code) = $args;
      
      $shelvedID = count(self::$shelf);
      
      self::$shelf[$shelvedID] = '<pre class="brush: ' . $brush . '">' . htmlspecialchars($code, ENT_NOQUOTES) . '</pre>';
      
      return '<wm:shelf(' . $shelvedID . ')>';
   }
   
   /*
    * replaces <exec>..</exec> syntax with placeholder (and puts PHP from it on "shelf")
    */
   
   private static function shelveExec($args)
   {
      list(, $code) = $args;
      
      // executing code
      
      ob_start();
      
      $evaluated = eval($code);
      
      $evaluated .= ob_get_clean();
      
      // shelving
      
      $shelvedID = count(self::$shelf);
      
      self::$shelf[$shelvedID] = $evaluated;
      
      return '<wm:shelf(' . $shelvedID . ')>';
   }
}

class Textile extends Textile_Extension{}