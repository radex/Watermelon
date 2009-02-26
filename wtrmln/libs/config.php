<?php
/********************************************************************

  Watermelon CMS

Copyright 2008-2009 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

/*
 * Lib Config
 * wersja 2.0.0
 * 
 * zarządzanie konfiguracją
 */

class Config
{
   public static $siteName;
   public static $siteSlogan;
   public static $hashAlgo;
   public static $defaultHashAlgo;
   public static $defaultController;
   public static $theme;
   private static $superusers = null;
   private static $dbconfig = array();
   private static $dbconfigLoaded = false;
   
   /*
    * public static void setSuperusers(string[] $superusers)
    * 
    * ustawia listę administratorów na $superusers
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
    * zwraca listę administratorów
    */
   
   public static function getSuperusers()
   {
      return self::$superusers;
   }
   
   /*
    * public static mixed getConf(string $fieldname)
    * 
    * pobiera z bazy danych wartość konfiguracji $fieldname i zwraca ją
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
    * ustawia w bazie danych wartość pola $fieldname na $fieldvalue
    */
   
   public static function setConf($fieldname, $fieldvalue)
   {
      self::$dbconfig[$fieldname] = $fieldvalue;
      
      DB::query("INSERT INTO `__config` (`field`, `value`) VALUES ('%1', '%2') ON DUPLICATE KEY UPDATE `value` = '%2'", $fieldname, $fieldvalue);
   }
}

?>
