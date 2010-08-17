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

class Watermelon
{
   /*
    * public static string[] $headData
    *
    * Array of data (tags) to put in <head> section in generated page
    */
   
   public static $headData = array();
   
   /*
    * public static void run()
    * 
    * Loads libraries, proper controller and generates a page
    */
   
   public static function run()
   {
      self::prepare();
      
      self::loadController();
      
      UnitTester::runTests();
      
      self::generate();
   }
   
   /*
    * private static void prepare()
    * 
    * Prepares Watermelon to actually run a controller and generate a page.
    * 
    * It does stuff like turning sessions and output buffering on, fixing magic quotes, loading libraries and helpers etc.
    */
   
   private static function prepare()
   {
      // running some stuff
      
      session_start();
      session_regenerate_id();

      ob_start();

      header('Content-Type: text/html; charset=UTF-8');
      
      // fixing "magic" quotes

      if(get_magic_quotes_gpc())
      {
         function stripslashes_deep($value)
         {
            $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
            return $value;
         }
         
         $_POST    = array_map('stripslashes_deep', $_POST);
         $_GET     = array_map('stripslashes_deep', $_GET);
         $_COOKIE  = array_map('stripslashes_deep', $_COOKIE);
         $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
      }
      
      // importing some variables from global scope
      
      global $_w_cmsDir, $_w_basePath;
      global $_w_dbHost, $_w_dbUser, $_w_dbPass, $_w_dbName, $_w_dbPrefix;
      global $_w_superuser, $_w_debugLevel, $_w_startTime;
      
      // setting proper error reporting mode, and debug constant in respect to internal debug level variable

      switch($_w_debugLevel)
      {
         case 0:
         default:
            error_reporting(0);
            break;
         case 1:
            error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);
            define('WM_DEBUG', '');
            break;
         case 2:
            error_reporting(E_ALL);
            define('WM_DEBUG', '');
            break;
      }
      
      // saving database configuration in array, and unsetting its variables for safety (saving it to Registry happens later)
      
      $dbConfig = array
         (
            'host'   => $_w_dbHost,
            'user'   => $_w_dbUser,
            'pass'   => $_w_dbPass,
            'name'   => $_w_dbName,
            'prefix' => $_w_dbPrefix
         );
      
      unset($_w_dbHost, $_w_dbUser, $_w_dbPass, $_w_dbName, $_w_dbPrefix);
      
      // Setting paths constants
      
      define('WM_Watermelon_Path', $_w_basePath . $_w_cmsDir . '/');
      define('WM_Core',            WM_Watermelon_Path . 'core/');
      
      define('WM_Libs',     WM_Core . 'libs/');
      define('WM_Helpers',  WM_Core . 'helpers/');
      define('WM_Tests',    WM_Core . 'tests/');
      
      define('WM_Modules',  WM_Watermelon_Path . 'modules/');
      define('WM_Uploaded', WM_Watermelon_Path . 'uploaded/');
      define('WM_Cache',    WM_Watermelon_Path . 'cache/');
      
      // loading libraries and helpers
      
      include WM_Libs    . 'libs.php';
      include WM_Helpers . 'helpers.php';
      
      // running DB and URI
      
      Registry::create('wmelon.db.config', ToObject($dbConfig), false, 'DB');
      
      DB::connect();
      URI::divide();
      
      // other config
      
      $modulesList = new stdClass;
      $modulesList->controllers = array
         (
            'e404' => 'watermelon/e404.php',
         );
      
      Registry::create('wmelon.modulesList',       $modulesList, true, 'Watermelon');
      Registry::create('wmelon.controllerHandler', null,    true, 'Watermelon');
      Registry::create('wmelon.defaultController', 'test',  true, 'Watermelon');
   }
   
   private static function loadController()
   {
      // URI stuff
      
      $appType    = URI::$appType;
      $segments   = URI::$segments;
      $controller = strtolower($segments[0]);
      $action     = strtolower($segments[1]);
      
      // getting configuration
      
      $modulesList       = Registry::get('wmelon.modulesList');
      $controllerHandler = Registry::get('wmelon.controllerHandler');
      $defaultController = Registry::get('wmelon.defaultController');
      
      //--
      
      var_dump('appType', $appType,
               'segments', $segments,
               'controller', $controller,
               'action', $action,
               'modules', $modulesList,
               'cntHandler', $controllerHandler,
               'defCnt', $defaultController);
      
      //--
      
      // if no controller is specified in URI, use default one.
      
      if(empty($segments))
      {
         $controller = $defaultController;
      }
      
      // check if controller exists
      
      $moduleExists = false;
      
      if(isset($modulesList->controllers[$controller]))
      {
         if(true) // check if _really_ exists
         {
            $moduleExists = true;
         }
      }
      
      // if controller don't exist, use controller handler if set, or 'e404' ("no page found" page) otherwise
      
      if(!$moduleExists)
      {
         if(is_string($controllerHandler))
         {
            $controller = $controllerHandler;
         }
         else
         {
            $controller = 'e404';
         }
      }
      
      // loading controller
      
      var_dump('==>', $controller);
      include WM_Modules . $modulesList->controllers[$controller];
      $controllerClassName = $controller.'_Controller';
      $cnt = new $controllerClassName;
      
      echo '-------------';
      $cnt->test();
   }
   
   /*
    * private static void generatePage(string $content)
    */
   
   private static function generate()
   {
      $content = ob_get_clean();
      
      // replacing made in simple manner links into HTML
      
      // $content = str_replace('href="$/',   'href="'   . WM_SITEURL, $content);
      // $content = str_replace('action="$/', 'action="' . WM_SITEURL, $content);
      
      define('WM_SkinPath', WM_Modules . 'wcmslay/');
      define('WM_THEMEURL', 'http://localhost/w/wmelon/modules/wcmslay/');
      
      include WM_SkinPath . 'skin.php';
      
      self::$headData = '<title>Test!</title>';
      
      $skin = new WCMSLay_skin($content, self::$headData);
      
      $skin->display();
   }
}