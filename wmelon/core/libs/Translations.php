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
    * public static void/array parseTranslationFile(string $filePath[, bool $return = false])
    * 
    * Parses translation file from $filePath, and adds translations from it if $return == false, or returns translation codes otherwise (used by caching engine)
    */
   
   public static function parseTranslationFile($filePath, $return = false)
   {
      
   }
   
   /*
    * public static void addTextTranslation(string $scope, string $original, string $translationText)
    * 
    * Adds text-based translation
    * 
    * string $scope           - scope of translation (module or library name)
    * string $original        - original text to be translated (text used when calling tr/gtr)
    * string $translationText - translated text
    */
   
   public static function addTextTranslation($scope, $original, $translationText)
   {
      
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
      
   }
   
   /*
    * public static string translate(string $scope, string $text)
    * 
    * Translates text
    * 
    * string $scope - scope of translation (module or library name)
    * string $text  - text to be translated
    * 
    * Once again - don't call it explicitly, use gtr() or tr() instead
    */
   
   public static function translate($scope, $text)
   {
      
   }
}

/*
 * string gtr(string $scope, string $text)
 * 
 * Translates text
 * 
 * string $scope - scope of translation (module or library name)
 * string $text  - text to be translated
 * 
 * In modules, use tr instead
 */

function gtr($scope, $text)
{
   
}

/*
 * string tr(string $text)
 * 
 * Translates text from current module scope
 * 
 * string $text - text to be translated
 * 
 * Use it in modules
 */


function tr($text)
{
   
}