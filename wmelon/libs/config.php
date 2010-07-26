<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2010 Radosław Pietruszewski.
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
 * Lib Config
 * wersja 2.0.1
 * 
 * configuration management
 */

class Config
{
   //public static $siteName;
   //public static $siteSlogan;
   public static $hashAlgo;
   //public static $defaultController;
   public static $theme;
   private static $superusers = null;
   private static $dbconfig = array(); //?
   private static $dbconfigLoaded = false; //?
   
   /*
    * public static void setSuperusers(string[] $superusers)
    * 
    * ustawia listę administratorów na $superusers             // () FIXTRANSLATE
    */
   
   public static function setSuperusers(array $superusers)
   {
      if(self::$superusers === NULL)
      {
         self::$superusers = $superusers;
      }
   }
   
   /*
    * public static string[] getSuperusers()
    * 
    * Returns list of superusers
    */
   
   public static function getSuperusers()
   {
      return self::$superusers;
   }
   
   /*
    * public static mixed getConf(string $fieldname)
    * 
    * Fetches value of $fieldname configuration
    */
   
   public static function getConf($fieldname)
   {
      if(!self::$dbconfigLoaded)
      {
         $data = DB::query("SELECT * FROM `__config`");
         
         while($field = $data->to_obj())
         {
            self::$dbconfig[$field->field] = $field->value;
         }
         
         self::$dbconfigLoaded = true;
      }
      
      return self::$dbconfig[$fieldname];
   }
   
   /*
    * public static void setConf(string $fieldname, string $fieldvalue)
    * 
    * ustawia w bazie danych wartość pola $fieldname na $fieldvalue             // ( ) FIXTRANSLATE
    */
   
   public static function setConf($fieldname, $fieldvalue)
   {
      self::$dbconfig[$fieldname] = $fieldvalue;
      
      DB::query("INSERT INTO `__config` (`field`, `value`) VALUES ('%1', '%2') ON DUPLICATE KEY UPDATE `value` = '%2'", $fieldname, $fieldvalue);
   }
}

?>
