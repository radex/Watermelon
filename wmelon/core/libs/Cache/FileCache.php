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
 * abstract class FileCache
 * 
 * Auxiliary class for caches based on filesystem
 * 
 * Just inherit, specify cache name (directory name) and you're done
 */

abstract class FileCache
{
   /*
    * protected abstract string directory()
    * 
    * Returns directory name to which cache items will be saved
    * 
    * Override it in your class
    */
   
   protected abstract function directory();
   
   /*
    * Returns contents of $id item
    */
   
   public function fetch($id)
   {
      
   }
   
   /*
    * Saves $content in $id item
    */
   
   public function save($id, $content)
   {
      
   }
   
   /*
    * Deletes $id item(s)
    */
   
   public function delete($id)
   {
      
   }
   
   /*
    * Clears cache
    */
   
   public function clear()
   {
      
   }
}