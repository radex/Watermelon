<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010-2011 Radosław Pietruszewski.
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
 * class Loader
 * 
 * Loading all kinds of modules - models, view, blocks and extensions
 */

spl_autoload_register(array('Loader', 'autoloader')); // registering Watermelon autoloader

class Loader
{
   public static function autoloader($className)
   {
      $className = strtolower($className);
      
      // core libs
      
      $coreLibs = array
         (
            'acpinfo'     => 'headers/ACPInfo.php',
            'blockset'    => 'headers/Blockset.php',
            
            'eventcenter' => 'EventCenter.php',
            
            'benchmark'   => 'testing/Benchmark.php',
            
            'form'        => 'Form/Form.php',
            'acptable'    => 'helpers/ACPTable.php',
            'adminquick'  => 'helpers/AdminQuick.php',
         );
      
      if(isset($coreLibs[$className]))
      {
         include $coreLibs[$className];
         return;
      }
      
      // models
      
      if(substr($className, -6) == '_model')
      {
         self::model(substr($className, 0, -6));
         return;
      }
      
      // blocksets
      
      if(substr($className, -9) == '_blockset')
      {
         self::module(substr($className, 0, -9), 'blockset');
         return;
      }
      
      // extensions
      
      if(isset(Watermelon::$config->modulesList->extensions[$className]))
      {
         self::module($className, 'extension');
         $className::init();
         return;
      }
      
      // core extensions/libraries
      
      $coreExtensions['frontendlibraries'] = 'FrontendLibraries/FrontendLibraries.extension.php';
      $coreExtensions['textile']           = 'Textile/textile.extension.php';
      
      if(isset($coreExtensions[$className]))
      {
         include WM_Core . $coreExtensions[$className];
         $className::init();
         return;
      }
   }
   
   /*
    * public static Model model(string $name)
    * 
    * Loads model with name = $name, and returns its object
    */
   
   public static function model($name)
   {
      return self::module($name, 'model');
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
         $module = Watermelon::$controller->bundleName;
      }
      
      // checking whether "skin view" exists, and returning proper view object
      
      $path      = WM_Bundles . $module . '/views/' . $name . '.view.php';
      $path_skin = WM_SkinPath . 'views/' . $module . '/' . $name . '.view.php';
      
      if(file_exists($path_skin))
      {
         return new View($path_skin);
      }
      else
      {
         return new View($path);
      }
   }
   
   /*
    * models, extensions, blocksets
    */
   
   private static function module($name, $typeName)
   {
      $name = strtolower($name);
      
      if($typeName == 'extension')
      {
         $className = $name;
      }
      else
      {
         $className = $name . '_' . $typeName;
      }
      
      // return if already loaded
      
      if(class_exists($className))
      {
         return new $className;
      }
      
      // loading
      
      if(!isset(Watermelon::$config->modulesList->{$typeName . 's'}[$name]))
      {
         throw new WMException('Requested module doesn\'t exist', 'Loader:doesNotExist');
      }
      
      $bundleName = Watermelon::$config->modulesList->{$typeName . 's'}[$name];
      
      include WM_Bundles . $bundleName . '/' . $name . '.' . $typeName . '.php';
      
      return new $className;
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