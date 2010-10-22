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
    * protected abstract static string directory()
    * 
    * Returns directory name to which cache items will be saved
    * 
    * Override it in your class
    */
   
   protected abstract static function directory();
   
   /*
    * public static string fetch(string $id)
    * 
    * Returns contents of $id item
    * 
    * string $id - name of item in cache
    * 
    * Throws [Cache:doesNotExist] exception if requested item doesn't exist
    */
   
   public static function fetch($id)
   {
      $path = static::itemPath($id);
      
      if(!file_exists($path))
      {
         throw new WMException('Requested cache item does not exist', 'Cache:doesNotExist');
      }
      
      include $path;
      
      return stripslashes($contents);
   }
   
   /*
    * public static void save(string $id, string $content)
    * 
    * Saves $content in $id item
    * 
    * string $id      - name of item in cache
    * string $content - content to be saved in specified item
    */
   
   public static function save($id, $content)
   {
      $content = addslashes($content);
      
      $fileContents = '<? $contents=\'' . $content . '\';';
      
      file_put_contents(static::itemPath($id), $fileContents, LOCK_EX);
   }
   
   /*
    * public static void delete(string $id[, string $id[, ...]])
    * 
    * Deletes $id item(s)
    * 
    * string $id - name of item in cache (you can specify more than one)
    */
   
   public static function delete($id1)
   {
      foreach(func_get_args() as $id)
      {
         unlink(static::itemPath($id));
      }
   }
   
   /*
    * public static void clear()
    * 
    * Clears cache
    */
   
   public static function clear()
   {
      $files = FilesForDirectory(WM_Cache . static::directory() . '/');
      
      foreach($files as $file)
      {
         unlink($file);
      }
   }
   
   /*
    * public static abstract bool doesExist(string $id)
    * 
    * Returns whether $id item exists in cache
    * 
    * string $id - name of item in cache
    */
   
   public static function doesExist($id)
   {
      if(file_exists(static::itemPath($id)))
      {
         return true;
      }
      else
      {
         return false;
      }
   }
   
   /*
    * path for $id item
    */
   
   protected static function itemPath($id)
   {
      return WM_Cache . static::directory() . '/' . $id . '.php';
   }
}