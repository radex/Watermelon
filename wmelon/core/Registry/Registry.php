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

include 'RegistryItem.php';

/*
 * class Registry
 * 
 * Data storing utility
 */

class Registry
{
   private static $items = array(); // [RegistryItem[]] - items dictionary
   
   /*
    * public static void create(string $name[, mixed $value = null])
    * 
    * Creates new item in Registry
    * 
    * string $name
    *    Identificator used to access an item
    *    Note that name is case-insensitive
    * 
    * mixed $value = null
    *    Value associated with name
    * 
    * Throws an exception if item with given name already exist [alreadyRegistered]
    */
   
   public static function create($name, $value = null)
   {
      $name = (string) $name;
      
      // if item with given name already exist
      
      if(isset(self::$items[$name]))
      {
         throw new WMException('Attempt to create already existing item "' . $name . '" in Registry', 'Registry:alreadyRegistered');
      }
      
      // registering
      
      self::$items[$name] = new RegistryItem($value);
      
      // if item is persistent and doesn't exist yet in database, create it
      
      // inserts serialized value (for cases where value is not string)
      // "ON DUPLICATE KEY UPDATE `name`=`name`" does nothing. It's just for not throwing exception if item already exists in database
      
      DB::query("INSERT INTO `__registry` SET `name` = '%1', `value` = '%2' ON DUPLICATE KEY UPDATE `name`=`name`", $name, serialize($value));
      
      // if didn't exist and was inserted, sets isSynced to TRUE
      
      if(DB::affectedRows() > 0)
      {
         self::$items[$name]->isSynced = true;
      }
   }
   
   /*
    * public static mixed get(string $name)
    * public mixed __invoke(string $name)
    *
    * Fetches value of an item with given name from Registry
    *
    * Throws an exception if item with given name doesn't exist [doesNotExist]
    */
   
   public static function get($name)
   {
      $name = (string) $name;
      
      self::throwIfDoesNotExist($name);
      
      // if item is persistent and not synchronized, first synchronize.
      
      if(!self::$items[$name]->isSynced)
      {
         $value = DBQuery::select('registry')->where('name', $name)->act();
         $value = $value->fetchObject()->value;
         $value = unserialize($value); // value saved in database is serialized, so we have to unserialize it
         
         self::$items[$name]->value    = $value;
         self::$items[$name]->isSynced = true;
      }
      
      //--
      
      return self::$items[$name]->value;
   }
   
   /*
    * public static void set(string $name, mixed $value)
    * public void __invoke(string $name, mixed $value)
    *
    * Sets value of an item with given name in Registry
    *
    * Throws an exception if item with given name doesn't exist [doesNotExist]
    */
   
   public static function set($name, $value)
   {
      $name = (string) $name;
      
      self::throwIfDoesNotExist($name);
      
      // changing value
      
      self::$items[$name]->value = $value;
      
      // synchronizing with database
      
      DBQuery::update('registry')->set('value', serialize($value))->where('name', $name)->act();
   }
   
   /*
    * public static void delete(string $name)
    *
    * Deletes item with given name from Registry
    *
    * Throws an exception if item with given name doesn't exist [doesNotExist]
    */
   
   public static function delete($name)
   {
      $name = (string) $name;
      
      self::throwIfDoesNotExist($name);
      
      // deleting
      
      unset(self::$items[$name]);
      
      DBQuery::delete('registry')->where('name', $name)->act();
   }
   
   /*
    * public static bool exists(string $name)
    *
    * Returns whether item with given name exists
    */
   
   public static function exists($name)
   {
      $name = (string) $name;
      
      return is_object(self::$items[$name]);
   }
   
   /**************************************************************************/
   
   public function __invoke()
   {
      $args = func_get_args();
      
      if(count($args) == 2)
      {
         self::set($args[0], $args[1]);
      }
      else
      {
         return self::get($args[0]);
      }
   }
   
   /*
    * throws an exception if item with given name doesn't exist
    */
   
   private static function throwIfDoesNotExist($name)
   {
      if(!is_object(self::$items[$name]))
      {
         throw new WMException('Attempt to access nonexisting item "' . $name . '" in Registry', 'Registry:doesNotExist');
      }
   }
}