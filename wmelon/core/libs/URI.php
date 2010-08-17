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

class URI
{
   /*
    * public static enum $appType
    * 
    * Type of running application - either URI::AppType_Site (website) or URI::AppType_Admin (admin control panel)
    * 
    * Type is set to AppType_Admin if first segment is "admin", or AppType_Site otherwise
    * 
    * Note that if first segment is "admin", it will be removed from ::$segments array
    * 
    * Value is defined by ::divide(), and before it is called, value equals NULL
    */
   
   public static $appType = null;
   
   const AppType_Site  = 0;
   const AppType_Admin = 1;
   
   /*
    * public static string[] $segments
    * 
    * Array of resource identificator segments (parts separated by "/")
    * 
    * Value is defined by ::divide(), and before it is called, value equals NULL
    */
   
   public static $segments = null;
   
   /*
    * public static void divide()
    * 
    * Divides resource identificator (part of URI after index.php containing information about module and action to call, and parameters to be sent to that action) to segments; fills ::$appType and ::$segments
    */
   
   public static function divide()
   {
      $resourceIdentificator = $_SERVER['PATH_INFO'] ? $_SERVER['PATH_INFO'] : '';
      
      // dividing
      
      $segments = array();
      
      foreach(explode('/', $resourceIdentificator) as $segment)
      {
         // ignoring empty segments
         
         if(!empty($segment))
         {
            $segments[] = $segment;
         }
      }
      
      // setting app type
      
      if($segments[0] == 'admin')
      {
         self::$appType = self::AppType_Admin;
         
         array_shift($segments);
      }
      else
      {
         self::$appType = self::AppType_Site;
      }
      
      //--
      
      self::$segments = $segments;
   }
}