<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010 Radosław Pietruszewski.
 //  
 //  Watermelon is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * class GenericCache
 * 
 * Common cache for storing all kinds of data
 * 
 * Use it for storing small amounts of data when you don't need specific format
 * 
 * If you need more data to store - inherit from this class and override directory() method, or (when you need more specific format or more efficient storing manner) inherit from FileCache, or even directly from Cache
 */

class GenericCache extends FileCache
{
   /*
    * public static string fetch(mixed $id)
    * 
    * Returns contents of $id item
    * 
    * mixed $id - name of item in cache
    * 
    * Throws [Cache:doesNotExist] exception if requested item doesn't exist
    */
   
   public static function fetch($id)
   {
      return unserialize(parent::fetch($id));
   }
   
   /*
    * public static void save(mixed $id, mixed $content)
    * 
    * Saves $content in $id item
    * 
    * mixed $id      - name of item in cache
    * mixed $content - content to be saved in specified item
    */
   
   public static function save($id, $content)
   {
      parent::save($id, serialize($content));
   }
   
   /*
    * public static void delete(mixed $id[, mixed $id[, ...]])
    * 
    * Deletes $id item(s)
    * 
    * mixed $id - name of item in cache (you can specify more than one)
    */
   
   // just inheriting
   
   /*
    * public static abstract bool doesExist(mixed $id)
    * 
    * Returns whether $id item exists in cache
    * 
    * mixed $id - name of item in cache
    */
    
   // just inheriting
   
   /*
    * cache directory name
    */
   
   protected static function directory()
   {
      return 'generic';
   }
   
   /*
    * path for $id
    */
   
   public static function itemPath($id)
   {
      return parent::itemPath(md5(serialize($id)));
   }
}