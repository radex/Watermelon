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
 * class View
 * 
 * View object
 * 
 * Notice that you don't create any View objects. You get them from Loader::view(), or its shortcut view().
 */

class View
{
   public $viewPath;   // path to view (set by Loader::view(), and then used by ->display() to load actual view)
   public $paramaters; // [dictionary] parameters to be passed to actual view
   
   /*
    * public void display()
    * 
    * Loads actual view
    */
   
   public function display()
   {
      
   }
   
   public function __get($name)
   {
      return $this->parameters[$name];
   }
   
   public function __set($name, $value)
   {
      $this->parameters[$name] = $value;
   }
}