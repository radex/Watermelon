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

include 'FileCache.php';
include 'GenericCache.php';

/*
 * abstract class Cache
 * 
 * Base class for caching classes
 */

abstract class Cache
{
   /*
    * public static abstract mixed fetch(mixed $id)
    * 
    * Returns contents of $id item
    * 
    * mixed $id - name of item in cache (usually string)
    */
   
   public abstract function fetch($id);
   
   /*
    * public static abstract void save(mixed $id, mixed $content)
    * 
    * Saves $content in $id item
    * 
    * mixed $id      - name of item in cache (usually string)
    * mixed $content - content to be saved in specified item (usually string)
    */
   
   public abstract function save($id, $content);
   
   /*
    * public static abstract void delete(mixed $id[, mixed $id[, ...]])
    * 
    * Deletes $id item(s)
    * 
    * mixed $id - name of item in cache (usually string; you can specify more than one)
    */
   
   public abstract function delete($id);
   
   /*
    * public static abstract void clear()
    * 
    * Clears cache
    */
   
   public abstract function clear();
   
   /*
   public function filterID($id); // changing ID (which might contain non-ASCII characters) to something more certain (like hash) - it's the point, where inheritance (instead of implementing interface) would be better
   public function expires();     // how long to retain cache item before auto-deletion [it could also be variable]
   */
}