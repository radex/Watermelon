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

class Registry
{
   private static $items = array(); // [RegistryItem[]] - items dictionary
   
   /*
    * public static void create(string $name[, mixed $value = null[, bool $isPersistent = false[, bool/string $isReadOnly = false]]])
    * 
    * Creates new item in Registry
    * 
    * string $name
    *    Identificator used to access an item
    * 
    * mixed $value = null
    *    Value associated with name
    *    Note that $value is treated as default value if $isPersistent is set to TRUE, which means that if item doesn't exist in database yet, newly created one will have value equaling default value
    * 
    * bool $isPersistent = false
    *    Whether the item's value is saved to database
    *    Note that:
    *       - item can't be private and persistent at the same time
    *       - $value is treated as default value if $isPersistent is set to TRUE, which means that if item doesn't exist in database yet, newly created one will have value equaling default value
    * 
    * bool/string $isReadOnly = false
    *    [bool]:
    *       Whether properties of an item are unchangeable
    *       Note that:
    *          - read-only item can be changed anyway if is also persistent (so it's not recommended to create item being both read-only and persistent)
    *          - being read-only is not saved in database if item is persistent (so be careful)
    *    [string]:
    *       Item is private (access to item is permited only to class, which name is given)
    *       Note that item can't be private and persistent at the same time
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name already exist (or has been invalidated) [alreadyRegistered]
    * - $isPersistent is wrong type [isPersistentWrongType]
    * - $isReadOnly is wrong type [readOnlyWrongType]
    * - item is set to be both private and persistent [privateAndPersistent]
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
      
      if(!is_bool($isReadOnly) && !is_string($isReadOnly))
      {
         throw new WMException('isReadOnly property has to be bool or string!', 'Registry:isReadOnlyWrongType');
      }
      
      // if item is set to be both private and persistent
      
      if(is_string($isReadOnly) && $isPersistent)
      {
         throw new WMException('Registry item can\'t be both private and persistent!', 'Registry:privateAndPersistent');
      }
      
      // registering
      
      self::$items[$name] = new RegistryItem($value, $isPersistent, $isReadOnly);
      
      // if item is persistent and doesn't exist yet in database, create it
      
      if($isPersistent)
      {
         // inserts serialized value (for cases where value is not string)
         // "ON DUPLICATE KEY UPDATE `registry_name`=`registry_name`" does nothing. It's just for not throwing exception if item already exists in database
         
         DB::query("INSERT INTO `__registry` SET `registry_name` = '%1', `registry_value` = '%2' ON DUPLICATE KEY UPDATE `registry_name`=`registry_name`", $name, serialize($value));
         
         // if didn't exist and was inserted, sets isSynced to TRUE
         
         if(DB::affectedRows() > 0)
         {
            self::$items[$name]->isSynced = true;
         }
      }
   }
   
   /*
    * public static mixed get(string $name)
    *
    * Fetches value of an item with given name from Registry
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * - item is private, and caller class is wrong [wrongPrivateItemClass]
    */
   
   public static function get($name)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist', 'wrongPrivateItemClass');
      
      // if item is persistent and not synchronized, first synchronize.
      
      if(self::$items[$name]->isPersistent && !self::$items[$name]->isSynced)
      {
         $value = DB::query("SELECT `registry_value` FROM `__registry` WHERE `registry_name` = '%1'", $name);
         $value = $value->toObj()->registry_value;
         $value = unserialize($value); // value saved in database is serialized, so we have to unserialize it
         
         self::$items[$name]->value    = $value;
         self::$items[$name]->isSynced = true;
      }
      
      //--
      
      return self::$items[$name]->value;
   }
   
   /*
    * public static void set(string $name, mixed $value)
    *
    * Sets value of an item with given name in Registry
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * - item is read-only [readOnly]
    * - item is private, and caller class is wrong [wrongPrivateItemClass]
    */
   
   public static function set($name, $value)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist', 'readOnly', 'wrongPrivateItemClass');
      
      // changing value
      
      self::$items[$name]->value = $value;
      
      // synchronizing with database (if persistent)
      
      if(self::$items[$name]->isPersistent)
      {
         DB::query("UPDATE `__registry` SET `registry_value` = '%1' WHERE `registry_name` = '%2'", serialize($value), $name);
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
    * - item is private, and caller class is wrong [wrongPrivateItemClass]
    */
   
   public static function delete($name)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist', 'readOnly', 'wrongPrivateItemClass');
      
      // deleting in database if persistent
      
      if(self::$items[$name]->isPersistent)
      {
         DB::query("DELETE FROM `__registry` WHERE `registry_name` = '%1'", $name);
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
    * - item is private, and caller class is wrong [wrongPrivateItemClass]
    */
   
   public static function invalidate($name)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist', 'readOnly', 'wrongPrivateItemClass');
      
      // deleting in database if persistent
      
      if(self::$items[$name]->isPersistent)
      {
         DB::query("DELETE FROM `__registry` WHERE `registry_name` = '%1'", $name);
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

   /*
    * public static bool isPrivate(string $name)
    *
    * Returns whether item with given name is private
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    */
    
   public static function isPrivate($name)
   {
      self::runThrowers($name, 'nameNotString', 'doesNotExist');

      return is_string(self::$items[$name]->isReadOnly);
   }
   
   #####
   ##### private methods
   #####
   
   /*
    * runs given exception throwers. Give $name as first argument, and throwers' string codes as following
    */
   
   private static function runThrowers($name)
   {
      $wantedThrowers = func_get_args();
      array_shift($wantedThrowers); // shifting $name off beginning of an array
      
      foreach($wantedThrowers as $thrower)
      {
         $methodName = 'throwIf' . $thrower;
         
         self::$methodName($name);
      }
   }
   
   /*
    * throws an exception if given item name is not string
    */
   
   private static function throwIfNameNotString($name)
   {
      if(!is_string($name))
      {
         throw new WMException('Item name in Registry has to be string!', 'Registry:nameNotString');
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
   
   /*
    * throws an exception if item with given name is private, and class attempting to access an item is not the same class as class specified in isReadOnly property. Don't call it explicitly. Use ::runThrowers() instead.
    */ 
   
   private static function throwIfWrongPrivateItemClass($name)
   {
      if(!is_string(self::$items[$name]->isReadOnly)) return;
      
      $backtrace = debug_backtrace();
      
      // [0] in backtrace is this function, [1] is ::runThrowers(), and [2] is method of this class invoking ::runThrowers()
      // [3] should be the same class as in isReadOnly
      // of eval(), in which case, [4] should be the same class as in isReadOnly
      
      if($backtrace[3]['function'] == 'eval' && !isset($backtrace[3]['class'])) // very important to check non-existence of class!
      {
         $callerClassPos = 4;
      }
      else
      {
         $callerClassPos = 3;
      }
      
      // checking if class names match
      
      if(strtolower(self::$items[$name]->isReadOnly) !== strtolower($backtrace[$callerClassPos]['class']))
      {
         throw new WMException('Attempt to access private item "' . $name . '" from other class than specified in isReadOnly property in Registry', 'Registry:wrongPrivateItemClass');
      }
   }
}