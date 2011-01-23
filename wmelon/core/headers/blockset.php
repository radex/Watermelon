<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2009-2011 Radosław Pietruszewski.
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
 * abstract class BlockSet
 * 
 * BlockSets base class
 */

abstract class BlockSet
{
   public function __construct()
   {
      $this->db     = new DB;
      $this->load   = new Loader;
      $this->config = new Config;
      
      // attempting to load model with the same name
      
      $className = substr(get_called_class(), 0, -9); // name of class - '_BlockSet'
      
      try
      {
         $this->model = Loader::model($className);
      }
      catch(WMException $e){}
   }
}