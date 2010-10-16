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

include 'textile.php';

class Textile_Extension extends Extension
{
   private static $textile;
   
   public static function onAutoload()
   {
      self::$textile = new Textile_Lib;
   }
   
   public static function textile($text)
   {
      return self::$textile->TextileThis($text);
   }
   
   public static function textileRestricted($text)
   {
      return self::$textile->TextileRestricted($text);
   }
}