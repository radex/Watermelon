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
         self::module(substr($className, 0, -6), 'model');
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
         include CorePath . $coreExtensions[$className];
         $className::init();
         return;
      }
   }
   
   /*
    * public static View view(string $name)
    * 
    * Loads view, and returns object representing it
    * 
    * If you want load local view (view from the same bundle as class you're requesting from), just pass its name
    * 
    * If you want load global view (view from other other bundle than class you're requesting from), pass '/bundleName/viewName' (unix filesystems analogy)
    */
   
   public static function view($name)
   {
      $name = strtolower($name);
      
      // getting name of bundle requested view belongs to
      
      if($name[0] == '/')
      {
         // global view
         
         $name   = substr($name, 1); // removing '/' from the beginning
         $name   = explode('/', $name);
         $module = $name[0];
         array_shift($name); // shifting module name off beginning of view path
         $name   = implode('/', $name);
      }
      else
      {
         // local view
         
         $module = Watermelon::$controller->bundleName;
      }
      
      // checking whether "skin view" exists, and returning proper view object
      
      $path      = BundlesPath . $module . '/views/' . $name . '.view.php';
      $path_skin = SkinPath . 'views/' . $module . '/' . $name . '.view.php';
      
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
      
      include BundlesPath . $bundleName . '/' . $name . '.' . $typeName . '.php';
      
      return new $className;
   }
}

/*
 * View View(string $name)
 * 
 * Handy shortcut for Loader::view()
 */

function View($name)
{
   return Loader::view($name);
}