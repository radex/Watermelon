<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008 Radosław Pietruszewski.
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
 * Lib PluginsConfigDatabase
 * wersja 1.0.1
 * 
 * Różne informacje związane z pluginami...
 * 
 */

class PluginsConfigDatabase
{
   public static $pcdb = null; // make it working
   
   function __construct()
   {
      if(Watermelon::$pcdb !== null)
      {
         foreach(Watermelon::$pcdb as $key => $var)
         {
            $this->$key = $var;
         }  
      }
   }
   
   function __destruct()
   {
      foreach($this as $key => $var)
      {
         Watermelon::$pcdb->$key = $var;
      }
   }
}

?>