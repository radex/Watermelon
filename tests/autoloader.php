<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2011 Radosław Pietruszewski.
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
 * Autoloader
 */

function tests_autoloader($className)
{
   $className = strtolower($className);

   $libPaths = array
      (
         'watermelon'  => 'core/Watermelon.php',
         'wmexception' => 'core/testing/Exception.php',
         'db'          => 'core/DB/DB.php',
         'dbquery'     => 'core/DB/DB.php',
         'dbresult'    => 'core/DB/DB.php',
         'config'      => 'core/Config.php',
         
         /* headers */
         
         'model'       => 'core/headers/Model.php',
         'extension'   => 'core/headers/Extension.php',
         
         /* extensions, models, etc. */
         
         'users_model' => 'bundles/watermelon/users.model.php',
         'users'       => 'bundles/watermelon/users.extension.php',
      );

   if(isset($libPaths[$className]))
   {
      require dirname(__FILE__) . '/../wmelon/' . $libPaths[$className];
   }
}

spl_autoload_register('tests_autoloader');

/*
 * Database
 */

function dbConnect()
{
   DB::connect('localhost', 'watermelon_tests', 'watermeloner', 'wtrmln123', 'wm_');
}