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

include 'RegistryEntity.php';

class Registry
{
   private static $entities = array(); // private static RegistryEntity[] $entities - entities dictionary
   
   /*
    * public static void add(string $name[, mixed $value = null[, bool $isImmutable = false]])
    * 
    * Adds entity to registry
    * 
    * string $name  - identificator used to get or set entity value
    * mixed  $value = null
    * bool        $isImmutable = false - whether properties of an entity are unchangeable
    * bool/string $isTransient = false - if TRUE, you'll be able to access an entity only once, and then it will be invalidated. String value works the same as TRUE, with the difference, that the access will be permited only to class, which name is given. Note that transient properties are also automatically immutable
    *
    * Throws an exception if entity with given name already exists (alreadyRegistered)
    */
   
   public static function add($name, $value = null, $isImmutable = false, $isTransient = false)
   {
      self::throwIfNameNotString($name);
      self::throwIfNotBool('isImmutable', $isImmutable);
      
      // if entity with given name exist
      
      if(isset(self::$entities[$name]))
      {
         throw new WMException('Próba zarejestrowania zarejestrowanej już jednostki w Rejestrze: ' . $name, 'Registry:alreadyRegistered');
      }
      
      // registering
      
      self::$entities[$name] = new RegistryEntity($value, $isImmutable, false);
   }
   
   /*
    * public static mixed get(string $name)
    *
    * Fetches value of an entity with given name from registry
    *
    * Returns null if such entity doesn't exist
    */
   
   public static function get($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      
      return self::$entities[$name]->value;
   }
   
   /*
    * public static void set(string $name, mixed $value)
    *
    * Sets value of an entity with given name in registry
    *
    * Throws en exception if:
    * - such entity doesn't exist (doesNotExist)
    * - entity is immutable (immutable)
    */
   
   public static function set($name, $value)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfImmutable($name);
      
      // changing value
      
      self::$entities[$name]->value = $value;
   }

   /*
    * public static bool isImmutable(string $name)
    *
    * Returns whether entity with given name is immutable
    *
    * Throws en exception if such entity doesn't exist (doesNotExist)
    */
   
   public static function isImmutable($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);

      return self::$entities[$name]->isImmutable;
   }
   
   /*
    * public static bool isTransient(string $name)
    *
    * Returns whether entity with given name is transient
    *
    * Throws en exception if such entity doesn't exist (doesNotExist)
    */
   
   public static function isTransient($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);

      return (self::$entities[$name]->isTransient !== false);
   }
   
   /*
    * public static bool exists(string $name)
    *
    * Returns whether entity with given name exists
    */
   
   public static function exists($name)
   {
      self::throwIfNameNotString($name);
      
      return is_object(self::$entities[$name]);
   }
   
   /*
    * public static void set(string $name)
    *
    * Deletes entity with given name in registry
    *
    * Similar to invalidating, but deleting does not reserve the name,
    * so it's possible to recreate an entity with the same name.
    *
    * Throws en exception if:
    * - such entity doesn't exist (doesNotExist)
    * - entity is immutable (immutable)
    */
   
   public static function delete($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfImmutable($name);
      
      // deleting
      
      unset(self::$entities[$name]);
   }
   
   /*
    * public static void invalidate(string $name)
    *
    * Invalidates entity with given name in registry - deletes it, and
    * reserves the name, so that it won't be possible to recreate entity
    * with the same name
    *
    * Similar to deleting, but invalidating also reserves name.
    *
    * Throws en exception if:
    * - such entity doesn't exist (doesNotExist)
    * - entity is immutable (immutable)
    */
   
   public static function invalidate($name)
   {
      self::throwIfNameNotString($name);
      self::throwIfDoesNotExist($name);
      self::throwIfImmutable($name);
      
      // invalidating
      
      self::$entities[$name] = '';
   }
   
   /*
    * throws an exception if given entity name is not string
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
    * throws an exception if entity with given name doesn't exist
    */
   
   private static function throwIfDoesNotExist($name)
   {
      if(!is_object(self::$entities[$name]))
      {
         throw new WMException('Próba dostępu do niezarejestrowanej jednostki w Rejestrze: ' . $name, 'Registry:doesNotExist');
      }
   }
   
   /*
    * throws an exception if entity with given name is immutable
    */
   
   private static function throwIfImmutable($name)
   {
      if(self::$entities[$name]->isImmutable)
      {
         throw new WMException('Próba dostępu do niezmiennej jednostki w Rejestrze: ' . $name, 'Registry:immutable');
      }
   }
}