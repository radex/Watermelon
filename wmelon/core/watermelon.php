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

Watermelon::run();

class Watermelon
{
   /*
    * public static enum $appType
    * 
    * Type of running application - either URI::AppType_Site (website) or URI::AppType_Admin (admin control panel)
    * 
    * Type is set to AppType_Admin if first segment is "admin", or AppType_Site otherwise
    */
   
   public static $appType;
   
   const AppType_Site  = 1;
   const AppType_Admin = 2;
   
   /*
    * public static string[] $segments
    * 
    * Array of resource identificator segments, stripped from controller and action name (if available)
    */
   
   public static $segments = array();
   
   /*
    * public static string $moduleName
    * 
    * Name of module currently running controller belongs to
    */
   
   public static $moduleName = '';
   
   /*
    * public static string $controllerName
    * 
    * Name of currently running controller
    */
   
   public static $controllerName = '';
   
   /*
    * public static object $modulesList
    * 
    * List of all kinds of module classes - controllers, models, views, etc.
    */
   
   public static $modulesList;          // TODO: complete documentation when done
   
   /*
    * public static void displayNoPageFoundError()
    * 
    * Loads 'e404' controller ("no page found" page)
    */
   
   public static function displayNoPageFoundError()
   {
      include WM_Modules . 'watermelon/e404.controller.php';
      
      self::$moduleName = 'watermelon';
      self::$controllerName = 'e404';
      
      $controllerObj = new e404_Controller();
      $controllerObj->index_action();
   }
   
   /*
    * public static void run()
    * 
    * Loads libraries, proper controller and generates a page
    */
   
   public static function run()
   {
      self::prepare();
      self::loadController();
      
      // tests
      
      include WM_Libs . 'Registry/Registry.test.php';
      UnitTester::runTest(new Registry_TestCase);
      
      // generating
      
      self::generate();
   }
   
   /*
    * private static void prepare()
    * 
    * Prepares Watermelon to run a controller and generate a page
    * 
    * It does stuff like turning sessions and output buffering on, fixing magic quotes, loading libraries and helpers etc.
    */
   
   private static function prepare()
   {
      define('WM', '');
      
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
      
      // base path
      
      $basePath = str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . '/';
      
      // loading configuration
      
      include $basePath . 'config.php';
      
      // setting proper error reporting mode, and debug constant in respect to internal debug level variable

      switch($debugLevel)
      {
         case 0:
         default:
            error_reporting(0);
         break;
         
         case 1:
            error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);
            define('WM_Debug', '');
         break;
         
         case 2:
            error_reporting(E_ALL);
            define('WM_Debug', '');
         break;
      }
      
      // dividing URI
      
      self::divide();
      
      // saving database configuration to array (saving it to Registry happens later)
      
      $dbConfig = array
         (
            'host'   => $dbHost,
            'user'   => $dbUser,
            'pass'   => $dbPass,
            'name'   => $dbName,
            'prefix' => $dbPrefix
         );
      
      unset($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix);
      
      // paths
      
      define('WM_BasePath', $basePath);
      define('WM_Core',     WM_BasePath . 'core/');
      
      define('WM_Libs',     WM_Core . 'libs/');
      define('WM_Helpers',  WM_Core . 'helpers/');
      define('WM_Tests',    WM_Core . 'tests/');
      
      define('WM_Modules',  WM_BasePath . 'modules/');
      define('WM_Uploaded', WM_BasePath . 'uploaded/');
      define('WM_Cache',    WM_BasePath . 'cache/');
      
      // loading libraries and helpers
      
      include WM_Libs    . 'libs.php';
      include WM_Helpers . 'helpers.php';
      
      // running DB
      
      Registry::create('wmelon.db.config', ToObject($dbConfig), false, 'DB');
      DB::connect();
      
      // other config
      
      $modulesList = new stdClass;
      $modulesList->controllers = array
         (
            'e404' => array('watermelon', false),
            'test' => array('test', false),
            'cnthnd' => array('test', true),
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
   
   /*
    * private static void divide()
    * 
    * Divides resource identificator (part of URI after index.php containing information about module and action to call, and parameters to be sent to that action) to segments; fills ::$appType and ::$segments
    */
   
   private static function divide()
   {
      $resourceIdentificator = $_SERVER['PATH_INFO'] ? $_SERVER['PATH_INFO'] : '';
      
      // dividing
      
      $segments = array();
      
      foreach(explode('/', $resourceIdentificator) as $segment)
      {
         // ignoring empty segments
         
         if(!empty($segment))
         {
            $segments[] = $segment;
         }
      }
      
      // setting app type
      
      if($segments[0] == 'admin')
      {
         self::$appType = self::AppType_Admin;
         
         array_shift($segments);
      }
      else
      {
         self::$appType = self::AppType_Site;
      }
      
      //--
      
      self::$segments = $segments;
   }
   
   /*
    * private static void loadController()
    * 
    * Determines which controller, and what action to run, and then runs it
    */
   
   private static function loadController()
   {
      // URI stuff
      
      self::$controllerName = strtolower(self::$segments[0]);
      $action               = strtolower(self::$segments[1]);
      
      // shortcuts
      
      $segments   = &self::$segments;
      $controller = &self::$controllerName;
      
      // controllers configuration
      
      $controllerHandler = Registry::get('wmelon.controllerHandler');
      $defaultController = Registry::get('wmelon.defaultController');
      
      $useControllerHandler = false;
      $useDefaultController = false;
      
      // determining controller to load
      
      if(empty($segments))
      {
         $controller = $defaultController;
         
         $useDefaultController = true;
      }
      else
      {
         // check if controller exists in modules list
         
         $controllerDetails = list($controllerPath, self::$moduleName) = self::controllerDetails($controller);
         
         if($controllerDetails != false)
         {
            array_shift($segments); // shifting controller name out of beginning of segments array
         }
         else
         {
            // if controller doesn't exist, use controller handler if set, or load error page otherwise
            
            if(is_string($controllerHandler))
            {
               $controller           = $controllerHandler;
               $useControllerHandler = true;
            }
            else
            {
               self::displayNoPageFoundError();
               return;
            }
         }
      }
      
      // loading controller details if loading default controller, or controller handler (and not controller from URI)
      
      if($useDefaultController || $useControllerHandler)
      {
         $controllerDetails = list($controllerPath, self::$moduleName) = self::controllerDetails($controller);
         
         if($controllerDetails == false)
         {
            self::displayNoPageFoundError();
         }
      }
      
      // loading controller
      
      include $controllerPath;
      
      $controllerClassName = $controller . '_Controller';
      $controllerObj       = new $controllerClassName;
      
      /// if controller handler is set, run it
      
      if($useControllerHandler)
      {
         $controllerObj->_controllerHandler(self::$segments);
         return;
      }
      
      // if action is not specified in URI, run default action
      
      if(count($segments) == 0)
      {
         $action = 'index';
         CallMethodQuietly($controllerObj, 'index_action');
         return;
      }
      
      // if action specified in URI exists, run it
      
      $actionName = $action . '_action';
      
      if(method_exists($controllerObj, $actionName))
      {
         array_shift($segments); // shifting action name out of beginning of segments array
         
         CallMethodQuietly($controllerObj, $actionName, $segments);
         return;
      }
      
      // if action handler exists in loaded controller, run it
      
      if(method_exists($controllerObj, '_actionHandler'))
      {
         CallMethodQuietly($controllerObj, '_actionHandler', $segments);
         return;
      }
      
      // if neither action specified in URI, nor action handler exists
      
      self::displayNoPageFoundError();
   }
   
   /*
    * private static array controllerDetails(string $controllerName)
    * 
    * Returns controller details - path, and module name it belongs to
    * 
    * Returned data is in format: array($path, $moduleName)
    * 
    * Used by ::loadController()
    */
   
   private static function controllerDetails($controllerName)
   {
      if(!isset(self::$modulesList->controllers[$controllerName]))
      {
         return false;
      }
      
      $info  = self::$modulesList->controllers[$controllerName];
      $path  = WM_Modules . $info[0] . ($info[1] == true ? '/controllers/' : '/') . $controllerName . '.controller.php';
      
      return array($path, $info[0]);
   }
   
   /*
    * private static void generatePage()
    * 
    * Generates page (loads skin etc)
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
      
      $nav = array(array
         (
            array('Foo', '#foo', null),
            array('Bar', '#bar', 'Bar!!!')
         ));
      
      $skin = new WCMSLay_skin;
      
      $skin->content    = &$content;
      $skin->headTags   = array('<foo bar>', '</foo bar>', '<title>test!</title>');
      $skin->pageTitle  = 'Tytuł podstrony';
      $skin->siteName   = 'Nazwa strony';
      $skin->siteSlogan = 'Slogan strony';
      $skin->footer     = 'Testowanie <em>stopki</em>…';
      $skin->blockMenus = null;
      $skin->textMenus  = $nav;
      
      $skin->display();
   }
}