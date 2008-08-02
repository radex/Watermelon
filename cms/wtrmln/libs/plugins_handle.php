<?php if(!defined('WTRMLN_IS')) die;
/********************************************************************

  Watermelon CMS

Copyright 2008 Radosław Pietruszewski

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
 * Lib PluginsHandle
 * wersja 1.3.0
 *
 * Łączność z pluginami / między pluginami
 *
 */

class PluginsHandle
{
   /*
    * private static PluginsHandle $instance
    *
    * Zawiera instancję tej klasy
    */
   private static $instance = NULL;

   /*
    * private array $data
    *
    * Dane PluginsHandle (dostęp od nich przez funkcje getData, putData i deleteDate)
    */

   private $data = array();

   /*
    * public mixed getData(string $module[, string $key]);
    *
    * Zwraca zawartość całego przedziału $module, lub
    * konkretnej komórki $module:$key, jeśli $key podane
    *
    */

   public function getData($module, $key = NULL)
   {
      $instance = self::Instance();

      if($key == NULL)
      {
         return $instance->data[$module];
      }
      else
      {
         return $instance->data[$module][$key];
      }
   }

   /*
    * public void putData(string $module, string $text)
    *
    * Wstawia w przedziale $module nową komórkę
    * o treści $text [w rzeczywistości odpala putDataNormal]
    *
    ***********************
    *
    * public void putData(string $module, string $key, string $text)
    *
    * Wstawia w przedziale $module nową komórkę
    * o nazwie $key, o treści $text [w rzeczywistości odpala putDataAssoc]
    *
    */

   public function putData($module, $key, $text = NULL)
   {
      $instance = self::Instance();

      if($text == NULL)
      {
         $text = $key; // żeby nie pomieszać

         $instance->putDataNormal($module, $text);
      }
      else
      {
         $instance->putDataAssoc($module, $key, $text);
      }
   }

   /*
    * public void deleteData(string $module, string $key)
    *
    * Usuwa komórkę $module:$key
    *
    */

   public function deleteData($module, $key)
   {
      $instance = self::Instance();

      unset($instance->data[$module][$key]);
   }

   /*
    * public void insertData(string $module, string $key, string $text)
    *
    * Dodaje $text do $module:$key
    *
    */

   public function insertData($module, $key, $text)
   {
      $instance = self::Instance();

      $instance->data[$module][$key] .= $text;
   }

   /*
    * public static PluginsHandle Instance()
    *
    * Singleton... (zwraca instancję tej klasy)
    *
    */

   public static function Instance()
   {
      if(!self::$instance instanceof self)
      self::$instance = new self;
      return self::$instance;
   }

   ##
   ## private
   ##

   /*
    * private void putDataNormal(string $module, string $text)
    *
    * Wstawia w przedziale $module nową komórkę
    * o treści $text
    *
    * Wywoływane poprzez putData(...)
    *
    */

   private function putDataNormal($module, $text)
   {
      $instance = self::Instance();

      $instance->data[$module][] = $text;
   }

   /*
    * private void putDataAssoc(string $module, string $key, string $text)
    *
    * Wstawia w przedziale $module nową komórkę
    * o nazwie $key, o treści $text
    *
    * Wywoływane poprzez putData(...)
    *
    */

   private function putDataAssoc($module, $key, $text)
   {
      $instance = self::Instance();

      $instance->data[$module][$key] = $text;
   }
}

?>
