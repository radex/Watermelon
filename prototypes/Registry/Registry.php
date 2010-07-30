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
   private static $items = array(); // private static RegistryItem[] $items - items dictionary
   
   /*
    * public static void add(string $name[, mixed $value = null[, bool/string $isReadOnly = false]])
    * 
    * Adds item to registry
    * 
    * string      $name               - identificator used to get or set item value
    * mixed       $value      = null  - value associated with name
    * bool/string $isReadOnly = false - [bool]   whether properties of an item are unchangeable
    *                                   [string] item is private (access to item is permited only to class, which name is given)
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - $isReadOnly is wrong type [readOnlyWrongType]
    * - item with given name already exist (or has been invalidated) [alreadyRegistered]
    */
   
   public static function add($name, $value = null, $isReadOnly = false)
   {
      self::throwIfNameNotString($name);
      self::throwIfReadOnlyWrongType($isReadOnly);
      
      // if item with given name already exist
      
      if(isset(self::$items[$name]))
      {
         throw new WMException('Próba zarejestrowania zarejestrowanej już jednostki w Rejestrze: ' . $name, 'Registry:alreadyRegistered');
      }
      
      // registering
      
      self::$items[$name] = new RegistryItem($value, $isReadOnly);
   }
   
   /*
    * public static mixed get(string $name)
    *
    * Fetches value of an item with given name from registry
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * - item is private, and caller class is wrong [wrongPrivateItemClass]
    */
   
   public static function get($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfWrongPrivateItemClass($name);
      
      return self::$items[$name]->value;
   }
   
   /*
    * public static void set(string $name, mixed $value)
    *
    * Sets value of an item with given name in registry
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * - item is read-only [readOnly]
    * - item is private, and caller class is wrong [wrongPrivateItemClass]
    */
   
   public static function set($name, $value)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfReadOnly($name);
      self::throwIfWrongPrivateItemClass($name);
      
      // changing value
      
      self::$items[$name]->value = $value;
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
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);

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
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);

      return is_string(self::$items[$name]->isReadOnly);
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
      self::throwIfNameNotString($name);
      
      return is_object(self::$items[$name]);
   }
   
   /*
    * public static void delete(string $name)
    *
    * Deletes item with given name in registry
    *
    * Similar to invalidating, but deleting does not reserve the name,
    * so it's possible to recreate an item with the same name.
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * - item is read-only [readOnly]
    * - item is private, and caller class is wrong [wrongPrivateItemClass]
    */
   
   public static function delete($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfReadOnly($name);
      self::throwIfWrongPrivateItemClass($name);
      
      // deleting
      
      unset(self::$items[$name]);
   }
   
   /*
    * public static void invalidate(string $name)
    *
    * Invalidates item with given name in registry - deletes it, and
    * reserves the name, so that it won't be possible to recreate item
    * with the same name
    *
    * Similar to deleting, but invalidating also reserves name.
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * - item is read-only [readOnly]
    * - item is private, and caller class is wrong [wrongPrivateItemClass]
    */
   
   public static function invalidate($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfReadOnly($name);
      self::throwIfWrongPrivateItemClass($name);
      
      // invalidating
      
      self::$items[$name] = '';
   }
   
   #####
   ##### private methods
   #####
   
   /*
    * throws an exception if given item name is not string
    */
   
   private static function throwIfNameNotString($name)
   {
      if(!is_string($name))
      {
         throw new WMException('Nazwa jednostki w Rejestrze musi być typu string!', 'Registry:nameNotString');
      }
   }
   
   /*
    * throws an exception if given isReadOnly property is wrong type (is neither bool nor string)
    */
   
   private static function throwIfReadOnlyWrongType($readOnlyProperty)
   {
      if(!is_bool($readOnlyProperty) && !is_string($readOnlyProperty))
      {
         throw new WMException('Atrybut isReadOnly musi być typu albo bool, albo string!', 'Registry:readOnlyWrongType');
      }
   }
   
   /*
    * throws an exception if item with given name doesn't exist
    */
   
   private static function throwIfDoesNotExist($name)
   {
      if(!is_object(self::$items[$name]))
      {
         throw new WMException('Próba dostępu do niezarejestrowanej pozycji "' . $name . '" w Rejestrze', 'Registry:doesNotExist');
      }
   }
   
   /*
    * throws an exception if item with given name is private, and class attempting to access an item is not the same class as class specified in isReadOnly property
    */ 
   
   private static function throwIfWrongPrivateItemClass($name)
   {
      if(!is_string(self::$items[$name]->isReadOnly)) return;
      
      $backtrace = debug_backtrace();
      
      // [0] in backtrace is this function, [1] is this class' method calling this function
      // [2] should be the same class as in isReadOnly
      // of eval(), in which case, [3] should be the same class as in isReadOnly
      
      if($backtrace[2]['function'] == 'eval' && !isset($backtrace[2]['class'])) // very important to check non-existence of class!
      {
         $callerClassPos = 3;
      }
      else
      {
         $callerClassPos = 2;
      }
      
      // checking if class names match
      
      if(strtolower(self::$items[$name]->isReadOnly) !== strtolower($backtrace[$callerClassPos]['class']))
      {
         throw new WMException('Próba dostępu do prywatnej pozycji "' . $name . '" z innej klasy niż określona w atrybucie isReadOnly w Rejestrze', 'Registry:wrongPrivateItemClass');
      }
   }
   
   /*
    * throws an exception if item with given name is read-only
    */
   
   private static function throwIfReadOnly($name)
   {
      if(self::$items[$name]->isReadOnly === true)
      {
         throw new WMException('Próba dostępu do niezmiennej pozycji "' . $name . '" w Rejestrze', 'Registry:readOnly');
      }
   }
}