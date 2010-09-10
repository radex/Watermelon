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
 * class Translations
 * 
 * Managing translations
 * 
 * Notice that you don't usually use this class explicitly. You only create translation files, and get translations using helpers tr() and gtr()
 */

include 'TranslationsCache.php';

class Translations
{
   /*
    * private static array $translations
    * 
    * Array containing all of loaded translations
    * 
    * $translations = array('scope' => $codes, ...)
    *    $codes = array('original text' => $translationCode, ...)
    */
   
   private static $translations = array();
   
   /*
    * public static void/array parseTranslationFile(string $filePath, string $scope[, bool $return = false])
    * 
    * Parses translation file from $filePath, and adds translations from it to $scope if $return == false, or returns translation codes otherwise (used by caching engine and test case)
    */
   
   public static function parseTranslationFile($filePath, $scope, $return = false)
   {
      $file = file_get_contents($filePath);
      $file = str_replace('<?exit?>', '', $file); // deleting < ?exit? > sentence
      $xml  = new SimpleXMLElement($file);
      
      $translations = array();
      
      // processing XML tree
      
      foreach($xml->item as $item)
      {
         // if text translation, processing to code translation first
         
         if($item->translation['type'] == 'text')
         {
            $code = self::addTextTranslation(null, null, (string) $item->translation, true);
         }
         else
         {
            $code = (string) $item->translation;
         }
         
         $translations[] = array((string) $item->original, $code);
      }
      
      // returning translation codes
      
      if($return)
      {
         return $translations;
      }
      
      // or adding translations
      
      foreach($translations as $item)
      {
         list($original, $translation) = $item;
         
         self::addCodeTranslation($scope, $original, $translation);
      }
   }
   
   /*
    * public static void addTextTranslation(string $scope, string $original, string $translationText[, bool $return = false])
    * 
    * Adds text-based translation
    * 
    * string $scope           - scope of translation (module or library name)
    * string $original        - original text to be translated (text used when calling tr/gtr)
    * string $translationText - translated text
    * bool   $return = false  - if true, instead of adding translation, translation code will be returned
    */
   
   public static function addTextTranslation($scope, $original, $translationText, $return = false)
   {
      // processing text translation into code
      
      $translationCode = 'return \''; // output translation code
      $argNumState     = false;       // TRUE when collecting numbers after '%' sign
      $argNumber       = '';          // arg number (figures after '%' sign)
      
      for($i = 0, $j = strlen($translationText); $i < $j; $i++)
      { 
         $char         = $translationText[$i];
         $prevChar     = $translationText[$i - 1];
         $prevPrevChar = $translationText[$i - 2];
         $nextChar     = $translationText[$i + 1];
         
         // if collecting arg number figures
         
         if($argNumState)
         {
            // if last char

            if($i == $j - 1)
            {
               $lastChar = true;
            }
            
            // if first figure; validation is already done
            
            if($argNumber == '')
            {
               $argNumber .= $char;
               
               if($lastChar)
               {
                  $translationCode .= $argNumber . '].\'';
               }
               
               continue;
            }
            
            // if number
            
            if($char >= '0' && $char <= '9')
            {
               $argNumber .= $char;
               
               if($lastChar)
               {
                  $translationCode .= $argNumber . '].\'';
               }
               
               continue;
            }
            else
            {
               $argNumState      = false;
               $translationCode .= $argNumber . '].\'';
               $argNumber        = '';
            }
         }
         
         // if current char is '%', previous char isn't '\' (or two previous chars are '\\'), and next char is a number
         
         if($char == '%' && ($prevChar != '\\' || $prevPrevChar == '\\') && $nextChar >= '1' && $nextChar <= '9')
         {
            $translationCode .= '\'.$args[';
            $argNumState = true;
            continue;
         }
         
         // if just a text char
         
         $translationCode .= $char;
      }
      
      $translationCode .= '\';';
      
      // adding or returning translation code
      
      if($return)
      {
         return $translationCode;
      }
      else
      {
         self::addCodeTranslation($scope, $original, $translationCode);
      }
   }
   
   /*
    * public static void addCodeTranslation(string $scope, string $original, string $translationCode)
    * 
    * Adds code-based translation
    * 
    * string $scope           - scope of translation (module or library name)
    * string $original        - original text to be translated (text used when calling tr/gtr)
    * string $translationCode - code, that will be executed to translate text
    */
   
   public static function addCodeTranslation($scope, $original, $translationCode)
   {
      self::$translations[$scope][$original] = $translationCode;
   }
   
   /*
    * public static string translate(string $scope, string $text[, $arg1[, $arg2[, ...]]])
    * 
    * Translates text
    * 
    * string $scope - scope of translation (module or library name)
    * string $text  - text to be translated
    * mixed  $args  - parameters, that will be passed to translation
    * 
    * Once again - don't call it explicitly, use gtr() or tr() instead
    */
   
   public static function translate($scope, $text)
   {
      // composing an array of translation parameters
      
      $args = func_get_args();
      array_shift($args);         // scope
      array_shift($args);         // text
      array_unshift($args, null); // args will start with 1 to maintain consistency with text translations
      
      //TODO: if doesn't exist
      
      // translating
      
      return eval(self::$translations[$scope][$text]);
   }
}

/*
 * string gtr(string $scope, string $text[, $arg1[, $arg2[, ...]]])
 * 
 * Translates text
 * 
 * string $scope - scope of translation (module or library name)
 * string $text  - text to be translated
 * mixed  $args  - parameters, that will be passed to translation
 * 
 * In modules, use tr instead
 */

function gtr($scope, $text)
{
   return call_user_func_array(array('Translations', 'translate'), func_get_args());
}

/*
 * string tr(string $text[, $arg1[, $arg2[, ...]]])
 * 
 * Translates text from current module scope
 * 
 * string $text - text to be translated
 * mixed  $args  - parameters, that will be passed to translation
 * 
 * Use it in modules
 */


function tr($text)
{
   $args = func_get_args();
   array_unshift($args, Watermelon::$moduleName);
   
   return call_user_func_array(array('Translations', 'translate'), $args);
}