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
 * Lib Loader
 * wersja 1.6.0
 * 
 * Ładowanie różnego rodzaju modułów
 * 
 */

include WTRMLN_LIBS . 'viewtags.php';

class Loader
{
   /* 
    * public static string view(string $view[, array $vars])
    * 
    * Pobiera i zwraca zawartość viewa
    * 
    * string $view - nazwa szablonu
    * array  $vars - przesyłane dane
    * 
    * $vars = array($varname => string $var[, $varname => string $var[, ... ]])
    *   $varname - nazwa zmiennej do zamiany
    *   $var     - treść zmiennej o nazwie $varname
    */
   
   public static function view($___view, array $___vars = array())
   {
      $___view = str_replace('_', '/', $___view);
      $___path_theme = WTRMLN_THEMEPATH . 'views/' . $___view . '.php';
      $___path = WTRMLN_VIEWS . $___view . '.php';
      
      // sprawdzamy czy istnieje theme view
      
      if(file_exists($___path_theme))
      {
         $___path = $___path_theme;
         $___dontcache = true;
      }

      // sprawdzamy czy view istnieje
         
      if(!file_exists($___path))
      {
         panic('Lib Loader: error 0<br>nie można załadować widoku "' . $___view . '"');
      }

      ob_start();
      
      // robimy przesyłane zmienne globalnymi
      
      foreach($___vars as $___varname => $___var)
      {
         $$___varname = $___var;
      }
      
      // tradycyjne ładowanie widoku używane
      // gdy cache'owanie jest wyłączone lub
      // wczytywany jest theme view
      
      if($___dontcache === true OR !defined('CACHE_VIEWS'))
      {
         $___eval = file_get_contents($___path);
         
         // przetwarzamy pseudotagi i wykonujemy
         
         $___eval = ViewTags::Process($___eval);
         echo eval('?>' . $___eval . '<?php ');
         
         //opróżnienie bufora
         
         $buffer = ob_get_contents();
         @ob_end_clean();
         return $buffer;
      }
      
      // jeśli cache'owanie jest włączone
      // sprawdzmy, czy widok jest już zacache'owany
      
      if(!$___eval = Cache::GetView($___view . '.php'))
      {
         $___eval = file_get_contents($___path);
         $___eval = ViewTags::Process($___eval);
         
         // cache'ujemy widok
         
         Cache::CacheView($___view . '.php', $___eval);
      }
      else
      {
         // wczytujemy zacache'owany widok
         
         $___eval = Cache::GetView($___view . '.php');
      }
      
      echo eval('?>' . $___eval . '<?php ');
      
      //opróżnienie bufora
      
      $buffer = ob_get_contents();
      @ob_end_clean();
      return $buffer;
   }
   
   /* 
    * public static Model model(string $modelName)
    * 
    * Zwraca obiekt modelu
    * 
    * string $model - nazwa modelu do wczytania
    */
   
   public static function model($model)
   {
      // preparujemy nazwę
      
      $model = strtolower($model);
      
      $model = 'model_' . $model;
      
      // sprawdzamy, czy już był załadowany
      
      if(class_exists($model))
      {
         $model_object = new $model();
         
         return $model_object;
      }
      
      // sprawdzamy, czy istnieje

      $path = WTRMLN_MODELS . $model . '.php';

      if(!file_exists($path))
      {
         panic('Lib loader: error 1<br>nie można załadować modelu');
      }
      
      // ładujemy
      
      include $path;
      
      $model_object = new $model();
      
      return $model_object;
   }
   
   /* 
    * public static string block(string $blockName)
    * 
    * Zwraca blok
    * 
    * string $block - nazwa bloku do wczytania
    */
   
   public static function block($blockName)
   {
      // preparujemy nazwę
      
      $blockName = strtolower($blockName);
      $class = 'Block' . $blockName;
      
      // sprawdzamy, czy już był załadowany
      
      if(class_exists($class))
      {
         ob_start();
         
         $block_object = new $class();
         
         $block_object->block();
         
         $buffer = ob_get_contents();
         @ob_end_clean();
         return $buffer;
      }
      
      // sprawdzamy, czy istnieje

      $path = WTRMLN_BLOCKS . $blockName . '.php';

      if(!file_exists($path))
      {
         panic('Lib loader: error 2<br>nie można załadować bloku');
      }
      
      // ładujemy
      
      include $path;
      
      ob_start();
      
      $block_object = new $class();
      
      $block_object->block();
      
      $buffer = ob_get_contents();
      @ob_end_clean();
      return $buffer;
   }
}

/*
 * Model model(string $model)
 * 
 * wczytuje model. Skrócona wersja Loader::model(...)
 * 
 * string $model - nazwa modelu do wczytania
 */

function model($model)
{
   return Loader::model($model);
}

/* 
 * string view(string $view[, array $vars])
 * 
 * Pobiera i zwraca zawartość viewa. Skrócona wersja Loader::view(...)
 * 
 * string $view - nazwa szablonu
 * array  $vars - przesyłane dane
 */

function view($view, array $vars = array())
{
   return Loader::view($view, $vars);
}


?>