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
    * public static abstract mixed fetch($id)
    * 
    * Returns contents of $id item
    * 
    * $id - name of item in cache
    * 
    * Throws [Cache:doesNotExist] exception if requested item doesn't exist
    */
   
   public static abstract function fetch($id);
   
   /*
    * public static abstract void save($id, $content)
    * 
    * Saves $content in $id item
    * 
    * $id      - name of item in cache
    * $content - content to be saved in specified item
    */
   
   public static abstract function save($id, $content);
   
   /*
    * public static abstract void delete($id[, $id[, ...]])
    * 
    * Deletes $id item(s)
    * 
    * $id - name of item in cache (you can specify more than one)
    */
   
   public static abstract function delete($id);
   
   /*
    * public static abstract void clear()
    * 
    * Clears cache
    */
   
   public static abstract function clear();
   
   /*
    * public static abstract bool doesExist($id)
    * 
    * Returns whether $id item exists in cache
    * 
    * $id - name of item in cache
    */
   
   public static abstract function doesExist($id);
}