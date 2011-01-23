<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2011 Radosław Pietruszewski.
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
   /*
    * Constructor
    * 
    * Note that Extensions are usually used statically, and constructor is usually never called. You can use ::init() instead.
    */
   
   public function __construct()
   {
      $this->db     = new DB;
      $this->load   = new Loader;
      $this->config = new Config;
      
      // attempting to load model with the same name
      
      $className = get_called_class();
      
      try
      {
         $this->model = Loader::model($className);
      }
      catch(WMException $e){}
   }
   
   /*
    * public static void init()
    * 
    * Function called when extension is loaded
    * 
    * Override it to perform actions (config loading etc.) before plugins is used
    */
   
   public static function init(){}
}