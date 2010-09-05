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

include 'CacheDriver.php';

/*
 * class Cache
 * 
 * Access to cache
 */

class Cache
{
   /*
    * public static mixed fetch(string $type, mixed $id)
    * 
    * Returns contents of $id item from $type cache
    * 
    * string $type - type of cache
    * mixed  $id   - name of item in cache (usually string)
    */
   
   public static function fetch($type, $id)
   {
      
   }
   
   /*
    * public static void save(string $type, mixed $id, mixed $content)
    * 
    * Saves $content in $id item in $type cache
    * 
    * string $type    - type of cache
    * mixed  $id      - name of item in cache (usually string)
    * mixed  $content - content to be saved in specified item (usually string)
    */
   
   public static function save($type, $id, $content)
   {
      
   }
   
   /*
    * public static void delete(string $type, mixed $id[, mixed $id[, ...]])
    * 
    * Deletes $id item(s) in $type cache
    * 
    * string $type - type of cache
    * mixed  $id   - name of item in cache (usually string; you can specify more than one)
    */
   
   public static function delete($type)
   {
      
   }
   
   /*
    * public static void clear(string $type[, string $type[, ...]])
    * 
    * Clears $type cache(s)
    * 
    * string $type - type of cache (you can specify more than one)
    */
   
   public static function clear()
   {
      
   }
   
   /*
    * public static void clearAll()
    * 
    * Clears all caches
    */
   
   public static function clearAll()
   {
      
   }
   
   /*
    * public static void registerDriver(string $type, CacheDriver $driverObject)
    * 
    * Registers $driverObject as Cache driver for $type
    */
   
   public static function registerDriver($type, CacheDriver $driverObject)
   {
      
   }
}