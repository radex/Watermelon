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
 * Class representing view
 * 
 * Notice that you don't create any View objects. You get them from Loader::view(), or its shortcut view().
 */

class View
{
   private $viewPath;             // path to view (set by Loader::view(), and then used by ->display() to load actual view)
   public  $parameters = array(); // [dictionary] parameters to be passed to actual view
   
   /*
    * public void display()
    * 
    * Loads actual view
    */
   
   public function display()
   {
      $view = new PHPTAL($this->viewPath);
      
      foreach($this->parameters as $key => $value)
      {
         $view->set($key, $value);
      }
      
      $view->setOutputMode(PHPTAL::HTML5);
      echo $view->execute();
   }
   
   public function __construct($viewPath)
   {
      $this->viewPath = $viewPath;
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