<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2011 Radosław Pietruszewski.
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
 * Managing database-based configuration
 */

/*
 * Field identificator pattern:
 * 
 * author.module[.subkey]*
 * 
 * Or in words: author name, '.', module name and then optionally subkeys joined with dots
 * 
 * author - your nick/name/company name etc. For Watermelon's modules it's 'wmelon'
 * module - name of module/plugin
 * 
 * Additional keys are actually object properties or array keys, so these two examples are equivalent:
 *    
 *    $fooBar = Config::get('johndoe.foo.bar');
 *    
 *    $fooBar = Config::get('johndoe.foo')->bar;
 * 
 * If you set values to non-existing keys by Config::set, these keys will be object properties. You can also assign an array to parent subkey, and it will work as well.
 * 
 * Identificators are case-insensitive.
 * 
 * More documentation soon.
 */

class Config
{
   /*
    * Values of fields
    */
   
   private static $fields = array();
   
   /*
    * Whether given fields _doesn't_ exist in database (for performance reasons, to reduce number of queries)
    */
   
   private static $noSuchField = array();
   
   /**************************************************************************/
   
   /*
    * public static mixed get(string $name)
    * public mixed __invoke(string $name)
    * 
    * Fetches value of $name field
    * 
    * For non-existing fields returns null
    */
   
   public static function get($name)
   {
      list($id, $keys) = self::parseName($name);
      
      // fetching root field
      
      if(array_key_exists($id, self::$fields))
      {
         $field = self::$fields[$id];
      }
      else
      {
         if(!self::$noSuchField[$id])
         {
            // checking in database
            
            $dbField = DBQuery::select('config')->where('name', $id)->act();
            
            if($dbField->exists)
            {
               $field = unserialize($dbField->fetchObject()->value);
               
               self::$fields[$id] = $field;
            }
            else
            {
               self::$noSuchField[$id] = true;
            }
         }
      }
      
      // returning requested subkey
      
      return self::getSubKey($field, $keys);
   }
   
   /*
    * public static void set(string $name, mixed $value)
    * public void __invoke(string $name, mixed $value)
    * 
    * Sets $name field to $value
    * 
    * When assigning to non-existing root field, new will be created
    * When assigning to non-existing subkey of a field, new will be created as object property
    */
   
   public static function set($name, $value)
   {
      list($id, $keys) = self::parseName($name);
      
      // setting (locally)
      
      self::setSubKey(self::$fields[$id], $keys, $value);
      
      // setting (in database)
      
      $field = serialize(self::$fields[$id]);
      
      DB::query("INSERT INTO __config (name, value) VALUES ('?', '?') ON DUPLICATE KEY UPDATE value = '?'", $id, $field, $field);
      
      unset(self::$noSuchField[$id]);
   }
   
   /*
    * public static void delete(string $name)
    * 
    * Deletes $name field from configuration
    */
   
   public static function delete($name)
   {
      list($id, $keys) = self::parseName($name);
      
      // deleting (locally)
      
      if(empty($keys))
      {
         unset(self::$fields[$id]);
      }
      else
      {
         self::deleteSubKey(self::$fields[$id], $keys);
      }
      
      // pushing changes to database
      
      if(!array_key_exists($id, self::$fields))
      {
         // deleting field completely
         
         DBQuery::delete('config')->where('name', $id)->act();
         
         self::$noSuchField[$id] = true;
      }
      else
      {
         // or only updating
         
         DBQuery::update('config')->where('name', $id)->set('value', serialize(self::$fields[$id]))->act();
      }
   }
   
   /*
    * public static bool exists(string $name)
    * 
    * Checks whether $name field exists in configuration
    */
   
   public static function exists($name)
   {
      list($id, $keys) = self::parseName($name);
      
      // fetching field
      
      if(array_key_exists($id, self::$fields))
      {
         $field = self::$fields[$id];
      }
      else
      {
         // checking if cached
         
         if(self::$noSuchField[$id])
         {
            return false;
         }
         else
         {
            // checking in database and caching result
            
            $dbField = DBQuery::select('config')->where('name', $id)->act();
            
            if($dbField->exists)
            {
               self::$fields[$id] = unserialize($dbField->fetchObject()->value);
               
               $field = self::$fields[$id];
            }
            else
            {
               self::$noSuchField[$id] = true;
               
               return false;
            }
         }
      }
      
      // returning final answer
      
      return self::subKeyExists($field, $keys);
   }
   
   /**************************************************************************/
   
   /*
    * Handy shortcut for configuration getter/setter
    */
   
   public function __invoke()
   {
      $args = func_get_args();
      
      if(count($args) == 1)
      {
         return self::get($args[0]);
      }
      else
      {
         return self::set($args[0], $args[1]);
      }
   }
   
   /**************************************************************************/
   
   /*
    * Gets particular subkey of object/array
    */
   
   private static function getSubKey(&$structure, array $keys)
   {
      // if already selected
      
      if(empty($keys))
      {
         return $structure;
      }
      
      // selecting
      
      $key = array_shift($keys);
      
      if(is_array($structure) && array_key_exists($key, $structure))
      {
         return self::getSubKey($structure[$key], $keys);
      }
      elseif(is_object($structure) && property_exists($structure, $key))
      {
         return self::getSubKey($structure->$key, $keys);
      }
      else
      {
         return null;
      }
   }
   
   /*
    * Sets particular subkey of object/array
    */
   
   private static function setSubKey(&$structure, array $keys, &$value)
   {
      // setting
      
      if(empty($keys))
      {
         $structure = $value;
         return;
      }
      
      // selecting
      
      $key = array_shift($keys);
      
      if(is_array($structure))
      {
         return self::setSubKey($structure[$key], $keys, $value);
      }
      elseif(is_object($structure))
      {
         return self::setSubKey($structure->$key, $keys, $value);
      }
      else
      {
         $structure->$key = null;
         
         return self::setSubKey($structure->$key, $keys, $value);
      }
   }
   
   /*
    * Checks if particular subkey of object/array exists
    */
   
   private static function subKeyExists(&$structure, array $keys)
   {
      // setting
      
      if(empty($keys))
      {
         return true;
      }
      
      // selecting
      
      $key = array_shift($keys);
      
      if(is_array($structure) && array_key_exists($key, $structure))
      {
         return self::subKeyExists($structure[$key], $keys);
      }
      elseif(is_object($structure) && property_exists($structure, $key))
      {
         return self::subKeyExists($structure->$key, $keys);
      }
      else
      {
         return false;
      }
   }
   
   /*
    * Deletes particular subkey of object/array
    */
   
   private static function deleteSubKey(&$structure, array $keys)
   {
      $key = array_shift($keys);
      
      if(is_array($structure))
      {
         if(empty($keys))
         {
            unset($structure[$key]);
         }
         else
         {
            return self::deleteSubKey($structure[$key], $keys);
         }
      }
      elseif(is_object($structure))
      {
         if(empty($keys))
         {
            unset($structure->$key);
         }
         else
         {
            return self::deleteSubKey($structure->$key, $keys);
         }
      }
   }
   
   /*
    * Converts field identificator to an array
    */
   
   private static function parseName($name)
   {
      $name = strtolower($name);
      $name = explode('.', $name);
      
      // stripping empty segments
      
      foreach($name as $segment)
      {
         if(!empty($segment))
         {
            $nameArray[] = $segment;
         }
      }
      
      // there must be more than two segments
      
      if(count($nameArray) < 2)
      {
         throw new InvalidArgumentException('Config field identificator must contain at least two segments');
      }
      
      // returning
      
      $id = $nameArray[0] . '.' . $nameArray[1];
      
      array_shift($nameArray);
      array_shift($nameArray);
      $keys = $nameArray;
      
      return array($id, $keys);
   }
}