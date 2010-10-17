<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2010 Radosław Pietruszewski.
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
 * abstract class Extension
 * 
 * Extensions base class
 */

abstract class Extension
{
   public function __construct()
   {
      $this->db       = new DB();
      $this->load     = new Loader();
      $this->registry = new Registry();
      
      // attempting to load model with the same name
      
      $className = substr(get_called_class(), 0, -10); // name of class - '_Extension'
      
      try
      {
         $this->model = Loader::model($className);
      }
      catch(WMException $e){}
   }
   
   /*
    * public static void onAutoload()
    * 
    * Function, that will be called on plugin autoload
    * 
    * Override it, if you need to do something on extensions auto-loading (but not on normal loading)
    */
   
   public static function onAutoload(){}
}