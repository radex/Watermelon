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
    * public static void add(string $name[, mixed $value = null[, bool $isReadOnly = false]])
    * 
    * Adds item to registry
    * 
    * string $name  - identificator used to get or set item value
    * mixed  $value = null
    * bool        $isReadOnly = false - whether properties of an item are unchangeable
//    * bool/string $isTransient = false - if TRUE, you'll be able to access an item only once, and then it will be invalidated. String value works the same as TRUE, with the difference, that the access will be permited only to class, which name is given. Note that transient properties are also automatically immutable
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - $isReadOnly isn't bool [propertyNotBool]
//    * - $isTransient is wrong type (neither bool nor string) [transienceWrongType]
    * - item with given name already exist (or has been invalidated) [alreadyRegistered]
    */
   
   public static function add($name, $value = null, $isReadOnly = false)//, $isTransient = false)
   {
      self::throwIfNameNotString($name);
      self::throwIfNotBool('isReadOnly', $isReadOnly);
      // self::throwIfTransienceWrongType($isTransient);
      
      // if item with given name already exist
      
      if(isset(self::$items[$name]))
      {
         throw new WMException('Próba zarejestrowania zarejestrowanej już jednostki w Rejestrze: ' . $name, 'Registry:alreadyRegistered');
      }
      
      // registering
      
      self::$items[$name] = new RegistryItem($value, $isReadOnly);//, $isTransient);
   }
   
   /*
    * public static mixed get(string $name)
    *
    * Fetches value of an item with given name from registry
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    // * - item is transient, and caller class is wrong [wrongTransienceClass]
    */
   
   public static function get($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      // self::throwIfWrongTransienceClass($name);
      
      //--
      
      $value = self::$items[$name]->value;
      /*
      // invalidate if transient
      
      if(self::isTransient($name))
      {
         self::$items[$name] = ''; // we're not calling self::invalidate(), because it would throw Registry:readOnly
      }*/
      
      //--
      
      return $value;
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
    */
   
   public static function set($name, $value)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfReadOnly($name);
      
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

      return self::$items[$name]->isReadOnly;
   }
/*
   /*
    * public static bool isTransient(string $name)
    *
    * Returns whether item with given name is transient
    *
    * Throws an exception if:
    * - $name isn't string [nameNotString]
    * - item with given name doesn't exist [doesNotExist]
    * /
    
   public static function isTransient($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);

      return (self::$items[$name]->isTransient !== false);
   }*/
   
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
    * public static void set(string $name)
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
    */
   
   public static function delete($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfReadOnly($name);
      
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
    */
   
   public static function invalidate($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfReadOnly($name);
      
      // invalidating
      
      self::$items[$name] = '';
   }
   
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
    * throws an exception if given property is not bool
    */
   
   private static function throwIfNotBool($name, $value)
   {
      if(!is_bool($value))
      {
         throw new WMException('Atrubut ' . $name . ' musi być typu bool!', 'Registry:propertyNotBool');
      }
   }
  /* 
   /*
    * throws an exception if given transience property is wrong type (is neither bool nor string)
    * /
   
   private static function throwIfTransienceWrongType($transienceProperty)
   {
      if(!is_bool($transienceProperty) && !is_string($transienceProperty))
      {
         throw new WMException('Atrybut isTransient musi być typu albo bool, albo string!', 'Registry:transienceWrongType');
      }
   }*/
   
   /*
    * throws an exception if item with given name doesn't exist
    */
   
   private static function throwIfDoesNotExist($name)
   {
      if(!is_object(self::$items[$name]))
      {
         throw new WMException('Próba dostępu do niezarejestrowanej jednostki w Rejestrze: ' . $name, 'Registry:doesNotExist');
      }
   }
   /*
   /*
    * throws an exception if item is transient, and class attempting to access an item is not the same class as class specified in isTransient property
    * / 
   
   private static function throwIfWrongTransienceClass($name)
   {
      if(is_string(self::$items[$name]->isTransient))
      {
         $backtrace = debug_backtrace();
         
         if(strtolower(self::$items[$name]->isTransient) !== strtolower($backtrace[2]['class']))
         {
            throw new WMException('Próba dostępu do jednostki krótkotrwałej z innej klasy niż określona w atrybucie isTransient jednostki: ' . $name, 'Registry:wrongTransienceClass');
         }
      }
   }*/
   
   /*
    * throws an exception if item with given name is read-only
    */
   
   private static function throwIfReadOnly($name)
   {
      if(self::$items[$name]->isReadOnly)
      {
         throw new WMException('Próba dostępu do niezmiennej jednostki w Rejestrze: ' . $name, 'Registry:readOnly');
      }
   }
}