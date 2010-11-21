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
 * Data storing facility. Beyond just storing data in array it also provides options to store data in database, and make item read-only
 */

class Registry
{
   private static $items = array(); // [RegistryItem[]] - items dictionary
   
   /*
    * public static void create(string $name[, mixed $value = null[, bool $isPersistent = false[, bool $isReadOnly = false]]])
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
    * bool $isPersistent = false
    *    Whether the item's value is saved to database
    *    Note that value is treated as default value if $isPersistent is set to TRUE, which means that if item doesn't exist in database yet, newly created one will have value equal default value
    * 
    * bool $isReadOnly = false
    *    Whether properties of an item are unchangeable
    *    Note that:
    *       - read-only item can be changed anyway if is also persistent
    *       - being read-only is not saved in database if item is persistent
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name already exist (or has been invalidated) [alreadyRegistered]
    * - $isPersistent is wrong type [isPersistentWrongType]
    * - $isReadOnly is wrong type [readOnlyWrongType]
    */
   
   public static function create($name, $value = null, $isPersistent = false, $isReadOnly = false)
   {
      self::runThrowers($name, 'nameNotString');
      
      // if item with given name already exist
      
      if(isset(self::$items[$name]))
      {
         throw new WMException('Attempt to create already existing item "' . $name . '" in Registry', 'Registry:alreadyRegistered');
      }
      
      // if $isPersistent is wrong type
      
      if(!is_bool($isPersistent))
      {
         throw new WMException('isPersistent property has to be bool!', 'Registry:isPersistentWrongType');
      }
      
      // if $isReadOnly is wrong type
      
      if(!is_bool($isReadOnly))
      {
         throw new WMException('isReadOnly property has to be bool!', 'Registry:isReadOnlyWrongType');
      }
      
      // registering
      
      self::$items[$name] = new RegistryItem($value, $isPersistent, $isReadOnly);
      
      // if item is persistent and doesn't exist yet in database, create it
      
      if($isPersistent)
      {
         // inserts serialized value (for cases where value is not string)
         // "ON DUPLICATE KEY UPDATE `name`=`name`" does nothing. It's just for not throwing exception if item already exists in database
         
         DB::query("INSERT INTO `__registry` SET `name` = '%1', `value` = '%2' ON DUPLICATE KEY UPDATE `name`=`name`", $name, serialize($value));
         
         // if didn't exist and was inserted, sets isSynced to TRUE
         
         if(DB::affectedRows() > 0)
         {
            self::$items[$name]->isSynced = true;
         }
      }
   }
   
   /*
    * public static mixed get(string $name)
    * public mixed __invoke(string $name)
    *
    * Fetches value of an item with given name from Registry
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    */
   
   public static function get($name)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist');
      
      // if item is persistent and not synchronized, first synchronize.
      
      if(self::$items[$name]->isPersistent && !self::$items[$name]->isSynced)
      {
         $value = DBQuery::select('registry')->where('name', $name)->execute();
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
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * - item is read-only [readOnly]
    */
   
   public static function set($name, $value)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist', 'readOnly');
      
      // changing value
      
      self::$items[$name]->value = $value;
      
      // synchronizing with database (if persistent)
      
      if(self::$items[$name]->isPersistent)
      {
         DBQuery::update('registry')->set('value', serialize($value))->where('name', $name)->execute();
      }
   }
   
   /*
    * public static void delete(string $name)
    *
    * Deletes item with given name in Registry
    *
    * Similar to invalidating, but deleting does not reserve the name, so it's possible to recreate an item with the same name.
    * 
    * Note that it also deletes value in database (if persistent)
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * - item is read-only [readOnly]
    */
   
   public static function delete($name)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist', 'readOnly');
      
      // deleting in database if persistent
      
      if(self::$items[$name]->isPersistent)
      {
         DBQuery::delete('registry')->where('name', $name)->execute();
      }
      
      // deleting locally
      
      unset(self::$items[$name]);
   }
   
   /*
    * public static void invalidate(string $name)
    *
    * Invalidates item with given name in Registry - deletes it, and reserves the name, so it's not possible to recreate item with the same name
    *
    * Similar to deleting, but invalidating also reserves name.
    *
    * Note that it also deletes value in database (if persistent)
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * - item is read-only [readOnly]
    */
   
   public static function invalidate($name)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist', 'readOnly');
      
      // deleting in database if persistent
      
      if(self::$items[$name]->isPersistent)
      {
         DBQuery::delete('registry')->where('name', $name)->execute();
      }
      
      // invalidating
      
      self::$items[$name] = '';
   }
   
   /*
    * public static bool exists(string $name)
    *
    * Returns whether item with given name exists
    * 
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    */
   
   public static function exists($name)
   {
      self::runThrowers($name, 'nameNotString');
      
      return is_object(self::$items[$name]);
   }

   /*
    * public static bool isPersistent(string $name)
    *
    * Returns whether item with given name is persistent
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    */
    
   public static function isPersistent($name)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist');

      return self::$items[$name]->isPersistent;
   }

   /*
    * public static bool isReadOnly(string $name)
    *
    * Returns whether item with given name is read-only
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    */
   
   public static function isReadOnly($name)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist');

      return self::$items[$name]->isReadOnly === true;
   }
   
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
   
   #####
   ##### private methods
   #####
   
   /*
    * runs given exception throwers. Give $name as first argument, and throwers' string codes as following
    * 
    * additionally, runs strtolower on name (to assure case-insensitivity)
    */
   
   private static function runThrowers(&$name)
   {
      // getting throwers' names
      
      $wantedThrowers = func_get_args();
      array_shift($wantedThrowers); // shifting $name off beginning of an array
      
      // running throwers
      
      foreach($wantedThrowers as $thrower)
      {
         $methodName = 'throwIf' . $thrower;
         
         self::$methodName($name);
      }
   }
   
   /*
    * throws an exception if given item name is not string, and runs strtolower on name (to assure case-insensitivity)
    */
   
   private static function throwIfNameNotString(&$name)
   {
      if(!is_string($name))
      {
         throw new WMException('Item name in Registry has to be string!', 'Registry:nameNotString');
      }
      else
      {
         // name is case-insensitive - strtolower-ing
      
         $name = strtolower($name);
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
   
   /*
    * throws an exception if item with given name is read-only
    */
   
   private static function throwIfReadOnly($name)
   {
      if(self::$items[$name]->isReadOnly === true)
      {
         throw new WMException('Attempt to access read-only item "' . $name . '" in Registry', 'Registry:readOnly');
      }
   }
}