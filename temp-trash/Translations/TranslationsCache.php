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
 * class TranslationsCache
 * 
 * private cache used by Translations class to optimize loading translations
 */

class TranslationsCache extends FileCache
{
   /*
    * public static string fetch(array $id)
    * 
    * Loads translations from $id item
    * 
    * array $id = array(string $module, string $langCode)
    *    string $module   - module of requested translations file
    *    string $langCode - language code (en, pl, ...)
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
      
      // loading file
      
      $code = file_get_contents($path);
      $code = str_replace('<?exit?>','',$code);
      
      eval($code);
   }
   
   /*
    * public static void save(array $id, array $translations)
    * 
    * Saves $translations in $id item
    * 
    * array $id = array(string $module, string $langCode)
    *    string $module   - module of requested translations file
    *    string $langCode - language code (en, pl, ...)
    * 
    * array $translations - array of translations to save in $id item
    *    = array(array(string $original, string $translationCode), ...)
    *       string $original        - original text to be translated
    *       string $translationCode - code, that will be executed to translate text
    */
   
   public static function save($id, $translations)
   {
      list($module, $langCode) = $id;
      
      $fileContents = '<?exit?>'; // to prevent direct executions
      
      // generating code
      
      foreach($translations as $translation)
      {
         list($original, $translationCode) = $translation;
         
         $fileContents .= "Translations::addCodeTranslation('" . addslashes($module) . "','" . addslashes($original) . "','" . addslashes($translationCode) . "');";
      }
      
      // saving
      
      file_put_contents(static::itemPath($id), $fileContents, LOCK_EX);
   }
   
   /*
    * public static void delete(array $id[, array $id[, ...]])
    * 
    * Deletes $id item(s)
    * 
    * array $id = array(string $module, string $langCode)
    *    string $module   - module of requested translations file
    *    string $langCode - language code (en, pl, ...)
    */
   
   // just inheriting
      
   /*
    * public static abstract bool doesExist(string $id)
    * 
    * Returns whether $id item exists in cache
    * 
    * array $id = array(string $module, string $langCode)
    *    string $module   - module of requested translations file
    *    string $langCode - language code (en, pl, ...)
    */
   
   // just inheriting
   
   /*
    * cache directory name
    */
   
   protected static function directory()
   {
      return 'translations';
   }
   
   /*
    * path for $id item
    */
   
   protected static function itemPath($id)
   {
      return CachePath . static::directory() . '/' . $id[0] . '.' . $id[1] . '.php';
   }
}