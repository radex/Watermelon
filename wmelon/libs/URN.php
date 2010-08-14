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

class URN
{
   /*
    * public static enum $appType
    * 
    * Type of running application - either URL::AppType_Site (website) or URL::AppType_Admin (admin control panel)
    * 
    * Value is defined by ::divide(), and before it is called, value equals NULL
    */
   
   public static $appType = null;
   
   public const AppType_Site  = 0;
   public const AppType_Admin = 1;
   
   /*
    * public static string[] $segments
    * 
    * Array of URN (filtered) segments
    * 
    * Value is defined by ::divide(), and before it is called, value equals NULL
    */
   
   public static $segments = null;
   
   /*
    * public static string $URNSource
    * 
    * URN to be parsed, divided to segments, and filtered by ::divide()
    * 
    * Default value is NULL, in which case ::divide() will use actual URN
    * 
    * Necessary for unit testing
    */
   
   public static $URNSource = null;
   
   /*
    * public static void divide()
    * 
    * Parses URN, divides it to segments, and filters them
    * 
    * Fills ::$appType, and ::$segments
    * 
    * If ::$URNSource is specified, it uses that variable's value instead of actual URN
    */
   
   public static function divide()
   {
      
   }
}