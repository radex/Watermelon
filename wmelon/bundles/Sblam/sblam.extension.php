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
 * Sblam! Extension
 */

include 'sblam.php';

class Sblam extends Extension
{
   /*
    * public static string $apiKey
    * 
    * API key for Sblam!
    */
   
   public static $apiKey;
   
   /*
    * onAutoload
    */
   
   public static function onAutoload()
   {
      // retrieving API key
      
      Registry::create('sblam.apiKey', null, true);
      
      self::$apiKey = Registry::get('sblam.apiKey');
      
      // display notice if admin and no API key set
      
      if(Watermelon::$appType == Watermelon::Admin && Watermelon::$segments == array() && empty(self::$apiKey))
      {
         Watermelon::addMessage('info', 'Filtr antyspamowy nie będzie działał poprawnie dopóki nie zostanie <a href="$/options/#sblamOptions">skonfigurowany</a>');
      }
   }
   
   /*
    * public static int test(string $text, string $author, string $email, string $website)
    * 
    * Tests given text/comment/post for spam
    * 
    * string $text, $author, $email, $website - *names* of $_POST fields to be tested. Leave NULL if a field isn't used
    * 
    * Returns:
    *    2  - certainly a spam
    *    1  - probably a spam (but put it for moderation just to be sure)
    *    -1 - probably a clean post (-||-)
    *    -2 - certainly a clean post
    *    0  - error, post hasn't been tested (put it for moderation)
    */
   
   public static function test($text, $author, $email, $website)
   {
      // if no API key set
      
      if(!empty(self::$apiKey))
      {
         return sblamtestpost(array($text, $author, $email, $website), self::$apiKey);
      }
      else
      {
         return 0;
      }
   }
   
   /*
    * public static string reportLink()
    * 
    * Returns link (HTML), where user can report spam filter error
    */
   
   public static function reportLink()
   {
      return '<a href="' . sblamreporturl() . '">Zgłoś błąd filtru</a>.';
   }
   
   /*
    * public static string JS()
    * 
    * Returns HTML reference to JavaScript you should display after form, where you want to use Sblam!
    */
   
   public static function JS()
   {
      return '<script src="' . WM_BundlesURL . 'sblam/sblam.js.php"></script>';
   }
}