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
 * class Loader
 * 
 * Loading all kinds of modules - models, view, blocks and extensions
 */

class Loader
{
   /*
    * public static Model model(string $name)
    * 
    * Loads model with name = $name, and returns its object
    */
   
   public static function model($name)
   {
      $name      = strtolower($name);
      $className = $name . '_Model';
      
      // return if already loaded
      
      if(class_exists($className))
      {
         return new $className;
      }
      
      // loading model
      
      $pathInfo = Watermelon::$modulesList->models[$name];
      
      include WM_Modules . $pathInfo[0] . ($pathInfo[1] ? '/models/' : '/') . $name . '.model.php';
      
      return new $className;
   }
   
   /*
    * public static View view(string $name[, bool $isGlobal = false])
    * 
    * Loads view, and returns object representing it
    * 
    * If you want load local view (view from the same module as class you're requesting from), just pass its name in $name
    * 
    * If you want load global view (view from other other module than class you're requesting from), pass 'moduleName/viewName' in $name, and set $isGlobal to TRUE
    */
   
   public static function view($name, $isGlobal = false)
   {
      $name = strtolower($name);
      
      // getting module name, requested view belongs to
      
      if($isGlobal)
      {
         $name   = explode('/', $name);
         $module = $name[0];
         array_shift($name); // shifting module name off beginning of view path
         $name   = implode('/', $name);
      }
      else
      {
         $module = Watermelon::$moduleName;
      }
      
      // generating path
      
      $path = WM_Modules . $module . '/views/' . $name . '.view.php';
      
      // returning view object
      
      return new View($path);
   }
   
   /*
    * public static BlockSet blockSet(string $name)
    * 
    * Loads BlockSet, and returns its object
    */
   
   public static function blockSet($name)
   {
      $name      = strtolower($name);
      $className = $name . '_BlockSet';
      
      // return if already loaded
      
      if(class_exists($className))
      {
         return new $className;
      }
      
      // loading blockset
      
      $pathInfo = Watermelon::$modulesList->blocksets[$name];
      
      include WM_Modules . $pathInfo[0] . ($pathInfo[1] ? '/blocksets/' : '/') . $name . '.blockset.php';
      
      return new $className;
   }
   
   /*
    * public static Extension extension(string $name)
    * 
    * Loads extension, and returns its object
    */
   
   public static function extension($name)
   {
      $name      = strtolower($name);
      $className = $name . '_Extension';
      
      // return if already loaded
      
      if(class_exists($className))
      {
         return new $className;
      }
      
      // loading blockset
      
      $pathInfo = Watermelon::$modulesList->extensions[$name];
      
      include WM_Modules . $pathInfo[0] . ($pathInfo[1] ? '/extensions/' : '/') . $name . '.extension.php';
      
      return new $className;
   }
   
   /*
    * public static void translation(string $moduleName)
    * 
    * Loads translations file for $moduleName
    */
   
   public static function translation($moduleName)
   {
      $moduleName = strtolower($moduleName);
      
      Translations::parseTranslationFile(WM_Modules . $moduleName . '/languages/' . $moduleName . '.' . 'en' . '.php', $moduleName); // TODO: store language in DB
   }
}

/*
 * Model Model(string $name)
 * 
 * Handy shortcut for Loader::model()
 */

function Model($name)
{
   return Loader::model($name);
}

/*
 * View View(string $name[, bool $isGlobal = false])
 * 
 * Handy shortcut for Loader::view()
 */

function View($name, $isGlobal = false)
{
   return Loader::view($name, $isGlobal);
}

/*
 * BlockSet BlockSet(string $name)
 * 
 * Handy shortcut for Loader::blockSet()
 */

function BlockSet($name)
{
   return Loader::blockSet($name);
}

/*
 * Extension Extension(string $name)
 * 
 * Handy shortcut for Loader::extension()
 */

function Extension($name)
{
   return Loader::extension($name);
}