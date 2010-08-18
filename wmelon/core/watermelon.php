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
    * public static string[] $segments
    * 
    * Array of resource identificator (in URI) segments, stripped from controller and action name (if available)
    */
   
   public static $segments = array();
   
   /*
    * public static object $modulesList
    * 
    * List of all kinds of module classes - controllers, models, views, etc.
    * 
    * Structure of list:
    * 
    * $modules->controllers: string[]
    * $modules->models:      string[]
    * $modules->views =
    *    'moduleName' => array('viewName' => 'viewPath', ...), ...
    */
   
   public static $modulesList;          // TODO: complete documentation when done
   
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
      define('WM_Core',     WM_Watermelon_Path . 'core/');
      
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
            'test' => 'test/test.php',
            'cnthnd' => 'test/cnthnd.php',
         );
      $modulesList->models = array
         (
            'testmodel' => 'test/testmodel.model.php',
         );
      
      Registry::create('wmelon.modulesList',       $modulesList, true, 'Watermelon');
      Registry::set('wmelon.modulesList', $modulesList);
      self::$modulesList = $modulesList;
      
      Registry::create('wmelon.controllerHandler', null,    true, 'Watermelon');
      Registry::set('wmelon.controllerHandler', 'cnthnd');
      Registry::create('wmelon.defaultController', 'test',  true, 'Watermelon');
      Registry::set('wmelon.defaultController', 'test');
   }
   
   private static function loadController()
   {
      // URI stuff
      
      $appType    = URI::$appType;
      $segments   = URI::$segments;
      $controller = strtolower($segments[0]);
      $action     = strtolower($segments[1]);
      
      self::$segments = $segments;
      
      // getting configuration
      
      $controllerHandler = Registry::get('wmelon.controllerHandler');
      $defaultController = Registry::get('wmelon.defaultController');
      
      // other stuff
      
      $useControllerHandler = false;
      
      // determining controller to load
      
      if(empty($segments))
      {
         $controller = $defaultController;
      }
      else
      {
         // check if controller exists (in modules list, and in filesystem)
         
         $moduleExists = false;

         if(isset(self::$modulesList->controllers[$controller]))
         {
            $controllerPath = WM_Modules . self::$modulesList->controllers[$controller];

            if(file_exists($controllerPath))
            {
               $moduleExists = true;
               array_shift(self::$segments); // shifting controller name out of beginning of segments array
            }
         }
         
         // if controller doesn't exist, use controller handler if set, or load error page otherwise

         if(!$moduleExists)
         {
            if(is_string($controllerHandler))
            {
               $controller           = $controllerHandler;
               $useControllerHandler = true;
            }
            else
            {
               self::loadErrorPage(); // TODO: details
               return;
            }
         }
      }
      
      // loading controller
      
      $controllerPath      = WM_Modules . self::$modulesList->controllers[$controller];
      $controllerClassName = $controller . '_Controller';
      
      include $controllerPath;
      
      $controllerObj = new $controllerClassName;
      
      /// if controller handler is set, run it
      
      if($useControllerHandler)
      {
         $controllerObj->_controllerHandler(self::$segments);
         return;
      }
      
      // if action is not specified in URI, run default action
      
      if(count($segments) <= 1)
      {
         self::invokeMethod($controllerObj, 'index_action');
         return;
      }
      
      // if action specified in URI exists, run it
      
      $actionName = $action . '_action';
      
      if(method_exists($controllerObj, $actionName))
      {
         array_shift(self::$segments); // shifting action name out of beginning of segments array
         
         self::invokeMethod($controllerObj, $actionName, self::$segments);
         return;
      }
      
      // if action handler exists in loaded controller, run it
      
      if(method_exists($controllerObj, '_actionHandler'))
      {
         self::invokeMethod($controllerObj, '_actionHandler', self::$segments);
         return;
      }
      
      // if neither action specified in URI, nor action handler exists
      
      self::loadErrorPage(); // TODO: details
   }
   
   private static function loadErrorPage()
   {
      $modulesList       = Registry::get('wmelon.modulesList');
      
      include WM_Modules . $modulesList->controllers['e404'];
      
      $controllerObj = new e404_Controller();
      
      $controllerObj->index_action();
   }
   
   private static function invokeMethod(&$object, $method, $args = array())
   {
      $reflection       = new ReflectionMethod(&$object, $method);
      $methodArgsNumber = $reflection->getNumberOfRequiredParameters();
      $args = (is_array($args)) ? $args : array();
      
      if(count($args) < $methodArgsNumber)
      {
         for($i = 0, $j = $methodArgsNumber - count($args); $i < $j; $i++)
         {
            $args[] = null;
         }
      }
      
      call_user_func_array(array(&$object, $method), $args);
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