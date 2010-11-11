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

class Sblam_Extension extends Extension
{
   public static $apiKey = 'Lnep34ioYivirpcwy4'; //TODO: use Registry, and auto-generate
   
   /*
    * public static int test(string $text, string $author, string $email, string $website)
    * 
    * Tests given text/comment/post for spam
    * 
    * string $text, $author, $email, $website - fields to be tested. Leave NULL if a field isn't used
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
      return sblamtestpost(array($text, $author, $email, $website), self::$apiKey);
   }
   
   
}

class Sblam extends Sblam_Extension{}