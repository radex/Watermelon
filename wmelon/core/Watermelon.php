<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2008-2011 Radosław Pietruszewski.
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

//TODO: refactor!

class Watermelon
{
   /*
    * public static string $requestURL
    * 
    * Original request URL string, e.g. 'foo/bar.xml?key=value'
    */
   
   public static $requestURL;
   
   /*
    * public static string[] $segments
    * 
    * Array of resource name segments, stripped from possible 'admin/' as well as controller and action names
    */
   
   public static $segments = array();
   
   /*
    * public static string $format
    * 
    * Requested output format sent in request URL like file extension (e.g. like in 'foo/bar.xml') or NULL if none specified
    */
   
   public static $format;
   
   /*
    * public static array $params
    * 
    * Object with parameters passed through URI, e.g. 'url?foo=bar' produces ->foo = 'bar'
    */
   
   public static $params;
   
   /**************************************************************************/
   
   /*
    * public static string[] $headTags
    * 
    * Array of tags to put before actual page content
    */
   
   public static $headTags = array();
   
   /*
    * public static string[] $tailTags
    * 
    * Array of tags to put after actual page content
    */
   
   public static $tailTags = array();
   
   /*
    * public static enum $appType
    * 
    * Type of running application:
    *    ::Site      - for website
    *    ::Admin     - for admin control panel
    *    ::Installer - for Watermelon installer
    */
   
   public static $appType;
   
   const Site      = 1;
   const Admin     = 2;
   const Installer = 3;
   
   /*
    * public static string $controllerName
    * 
    * Name of currently running controller
    */
   
   public static $controllerName = '';
   
   /*
    * public static string $actionName
    * 
    * Name of currently running action
    */
   
   public static $actionName = '';
   
   
   /*
    * public static Controller $controller
    * 
    * Currently running controller object
    */
   
   public static $controller;
   
   /*
    * public static object $config
    * 
    * Watermelon configuration
    * 
    * DO NOT CHANGE IT!
    * 
    * ---
    * Member variables:
    * 
    * modulesList       - list of all kinds of module classes - controllers, models, views, etc. (description of structure later)
    * controllerHandler - controller to run if there's no controller with name such as in URI
    * defaultController - controller to run if no controller name is given in URI
    * 
    * siteURL           - base URL for website pages
    * systemURL         - base URL for wmelon/ contents
    * 
    * skin              - website skin name
    * 
    * siteName          - website name
    * siteSlogan        - website slogan (some text, usually placed below site name; shown in some skins)
    * footer            - additional text (HTML) to put in footer
    * textMenus         - array of text-based menus ( -||- )
    * headTags          - tags to be added (string) at the beginning of the page
    * tailTags          - tags to be added (string) at the end of the page
    * 
    * ---
    * ModulesList structure:
    * 
    * 
    * $modulesList->controllers/models/blocksets/extensions/acpcontrollers =
    *    array($name => string $bundle, ...)
    *       string $name   - module name
    *       string $bundle - bundle, $name module belongs to
    * 
    * $moduleList->skins/acpinfofiles =
    *    array(string $bundle, ...)
    *       string $bundle - name of bundle with a skin/acp info file
    */
   
   public static $config;
   
   /**************************************************************************/
   
   /*
    * public static void displayError/displayNotice/displaySuccessNotice(string $message)
    * 
    * Sets message to be displayed on next generated page
    * 
    * If you stop the script (by refreshing or redirecting), messages will be displayed on the page after refreshing/redirection
    * 
    * When used in controller, call $this->displayX instead
    */
   
   public static function displayError($message)
   {   
      $_SESSION['wmelon.messages'][] = array('error', $message);
   }
   
   public static function displayNotice($message)
   {
      $_SESSION['wmelon.messages'][] = array('notice', $message);
   }
   
   public static function displaySuccessNotice($message)
   {
      $_SESSION['wmelon.messages'][] = array('successNotice', $message);
   }
   
   /*
    * public static void displayNoPageFoundError()
    * 
    * Loads 'e404' controller ("no page found" page)
    */
   
   public static function displayNoPageFoundError()
   {
      include BundlesPath . 'watermelon/e404.controller.php';
      
      self::$controllerName = 'e404';
      
      self::$controller = $controller = new e404_Controller();
      $controller->bundleName = 'watermelon';
      $controller->index_action();
   }
   
   /**************************************************************************/
   
   /*
    * public static void run()
    * 
    * Loads libraries, proper controller and generates a page
    */
   
   public static function run()
   {
      self::prepare();
      
      // if installer - skipping configuration etc, just loading controller and generating
      
      if(self::$appType == self::Installer)
      {
         include BundlesPath . 'installer/installer.controller.php';
         
         self::$controllerName   = 'installer';
         
         self::$controller = $controller = new Installer_Controller;
         
         $controller->bundleName = 'installer';
         $controller->installer();
         $controller->generate();
         
         return;
      }
      
      // auto-loading extensions
      
      new FrontendLibraries;
      
      if(self::$appType == self::Admin)
      {
         new Sblam;
      }
      
      // indexing modules (if debug or admin logged)
      
      if(defined('WM_Debug') || Users::isLogged())
      {
         self::indexModules();
      }
      
      // if ACP - checking if logged in
      
      if(self::$appType == self::Admin)
      {
         if(!Users::adminPrivileges())
         {
            SiteRedirect('users/login/' . base64_encode(self::$requestURL), 'site');
            exit;
         }
      }
      
      // Atom feed shortut
      
      if(self::$segments == array('feed.atom'))
      {
         self::$segments = array('blog', 'feed');
      }
      
      // blog posts shortcut
      
      if(count(self::$segments) >= 3 &&      // year/month/name
         is_numeric(self::$segments[0]) &&   // year
         is_numeric(self::$segments[1]))     // month
      {
         self::$segments = array('blog', '_post', self::$segments[2]);
      }
      
      // loading controller and generating
      
      self::loadController();
      self::$controller->generate();
   }
   
   /*
    * private static void prepare()
    * 
    * Prepares Watermelon to run a controller and generate a page
    * 
    * It does stuff like turning sessions and output buffering on, fixing magic quotes, loading libraries and helpers, loading configuration, setting constants etc.
    */
   
   private static function prepare()
   {
      define('WM', '');
      
      session_name('Watermelon');
      session_start();
      session_regenerate_id();
      
      ob_start();
      
      header('Content-Type: text/html; charset=UTF-8');
      
      define('WM_StartTime', microtime());
      define('WM_StartMemory', memory_get_usage());
      
      set_include_path('.');
      
      mb_internal_encoding('UTF-8');
      
      // fixing "magic" quotes
      
      if(get_magic_quotes_gpc())
      {
         function stripslashes_deep($value)
         {
            $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
            return $value;
         }
         
         $_POST    = array_map('stripslashes_deep', $_POST);
         $_COOKIE  = array_map('stripslashes_deep', $_COOKIE);
         $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
      }
      
      // paths
      
      $basePath = str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . '/';
      
      define('SystemPath',   $basePath);
      define('CorePath',     SystemPath . 'core/');
      define('BundlesPath',  SystemPath . 'bundles/');
      define('UploadedPath', SystemPath . 'uploaded/');
      define('CachePath',    SystemPath . 'cache/');
      
      // loading config file
      
      include $basePath . 'config.php';
      
      // checking if installer
      
      if(!isset($dbHost))
      {
         self::$appType = self::Installer;
         
         $installer = true;
         
         error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);define('WM_Debug', ''); //TODO: swap comments
         //error_reporting(0);
      }
      else
      {
         // setting proper error reporting mode and debug constant
      
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
      }
      
      // creating messages array in session
      
      if(!is_array($_SESSION['wmelon.messages']))
      {
         $_SESSION['wmelon.messages'] = array();
      }
      
      // URL-s
      
      $requestURL = self::getRequestURL();
      
      list($segments, $format, $params) = self::parseRequestURL($requestURL);
      
      if(self::$appType != self::Installer)
      {
         if($segments[0] == 'admin')
         {
            self::$appType = self::Admin;
            
            array_shift($segments);
         }
         else
         {
            self::$appType = self::Site;
         }
      }
      
      self::$requestURL = $requestURL;
      self::$segments   = $segments;
      self::$format     = $format;
      self::$params     = $params;
      
      // including libraries
      
      $libs = array
      (
         'helpers/helpers.php',

         'DB/DB.php',
         'Loader.php',
         'Config.php',
         'EventCenter.php',

         'testing/Exception.php',

         'headers/Controller.php',
         'headers/Skin.php',
         'headers/View.php',
         'headers/Model.php',
         'headers/Extension.php',
      );

      foreach($libs as $file)
      {
         include CorePath . $file;
      }
      
      // and that's it for the installer
      
      if($installer)
      {
         return;
      }
      
      // MySQL connection
      
      DB::connect($dbHost, $dbName, $dbUser, $dbPass, $dbPrefix);
      
      // getting main configuration array from Config
      
      $w = Config::get('wmelon.wmelon');
      
      self::$config = &$w;
      
      // setting constants
      
      define('SiteURL',     $w->siteURL);
      define('AdminURL',    $w->siteURL . 'admin/');
      define('SystemURL',   $w->systemURL);
      define('BundlesURL',  SystemURL . 'bundles/');
      define('UploadedURL', SystemURL . 'uploaded/');
      define('CacheURL',    SystemURL . 'cache/');
      
      if(self::$appType == self::Admin)
      {
         define('SkinPath', SystemPath    . 'core/ACPSkin/');
         define('SkinURL',  SystemURL . 'core/ACPSkin/');
         define('CurrURL',  AdminURL);
      }
      else
      {
         define('SkinPath', BundlesPath    . $w->skin . '_skin/');
         define('SkinURL',  BundlesURL . $w->skin . '_skin/');
         define('CurrURL',  SiteURL);
      }
   }
   
   /*
    * public static object indexModules([bool $save = true])
    * 
    * Scans bundles for module files - controllers, models, extensions etc., to create list of them
    * 
    * bool $save - if TRUE, list is saved to config, otherwise it's returned
    * 
    * Don't call it.
    */
   
   public static function indexModules($save = true)
   {
      $modulesList = new stdClass;
      
      $modulesList->controllers = array();
      $modulesList->models      = array();
      $modulesList->blocksets   = array();
      $modulesList->extensions  = array();
      $modulesList->skins       = array();
      $modulesList->acpcontrollers = array();
      $modulesList->acpinfofiles   = array();
      
      // searching
      
      foreach(new DirectoryIterator(BundlesPath) as $dir)
      {
         // if not a bundle, or Installer bundle
         
         if(!$dir->isDir() || $dir->isDot() || $dir->getFilename() == 'installer')
         {
            continue;
         }
         
         $bundleName = $dir->getFilename();
         
         // skins
         
         if(substr($bundleName, -5) == '_skin' && file_exists(BundlesPath . $bundleName . '/skin.php'))
         {
            $modulesList->skins[] = $bundleName;
         }
         
         // acp info files
         
         if(file_exists(BundlesPath . $bundleName . '/' . $bundleName . '.acpinfo.php'))
         {
            $modulesList->acpinfofiles[] = $bundleName;
         }
         
         // modules
         
         $moduleTypes = array('controller', 'model', 'blockset', 'extension');
         
         $files = FilesForDirectory(BundlesPath . $bundleName, false, true);

         foreach($files as $file)
         {
            // controllers, models, blocksets and extensions
            
            foreach($moduleTypes as $moduleType)
            {
               $ext = '.' . $moduleType . '.php';
               $extLen = strlen($ext);
               
               if(substr($file->getFilename(), -$extLen) == $ext)
               {
                  // .acp.controller.php and .controller.php are different module types
                  
                  if(substr($file->getFilename(), -($extLen + 4)) == '.acp' . $ext)
                  {
                     continue;
                  }
                  
                  // adding
                  
                  $moduleName = substr($file->getFilename(), 0, -$extLen);
                  
                  $modulesList->{$moduleType . 's'}[$moduleName] = $bundleName;
               }
            }
            
            // ACP controllers
            
            $ext = '.acp.controller.php';

            if(substr($file->getFilename(), -19) == $ext)
            {
               $moduleName = substr($file->getFilename(), 0, -19);
               
               $modulesList->acpcontrollers[$moduleName] = $bundleName;
            }
         }
      }
      
      // saving or returning
      
      if($save)
      {
         // if something has changed
         
         if(serialize($modulesList) != serialize(self::$config->modulesList))
         {
            self::$config->modulesList = $modulesList;
            
            Config::set('wmelon.wmelon', self::$config);
         }
      }
      else
      {
         return $modulesList;
      }
   }
   
   /**************************************************************************/
   
   /*
    * private static string getRequestURL()
    * 
    * Returns request URL
    * 
    * For instance, both
    *    http://some.website.com/website/foo/bar
    * and
    *    http://some.website.com/website/index.php/foo/bar/
    * 
    * will produce:
    *    foo/bar
    */
   
   private static function getRequestURL()
   {
      $url = $_SERVER['REQUEST_URI'];        // e.g. /website/index.php/bar or /website/bar
      
      $scriptName = $_SERVER['SCRIPT_NAME']; // e.g. /website/index.php or /index.php
      
      // determining directory of index.php
      
      $indexDir = substr($scriptName, 0, -9);
      
      // determining URL without possible directory in the beginning
      // e.g. index.php/foo/bar or foo/bar
      
      $url = (string) substr($url, strlen($indexDir));
      
      // deleting 'index.php/' from the beginning (if present)
      
      if(strpos($url, 'index.php/') === 0)
      {
         $url = (string) substr($url, 10);
      }
      elseif($url == 'index.php')
      {
         $url = '';
      }
      
      return $url;
   }
   
   /*
    * private static array parseRequestURL(string $url)
    * 
    * Parses given request URL into segments, extension and params
    * 
    * Returned:
    *    array(string[] $segments, string $format, array $params)
    *       string[] $segments - path, e.g. array('blog', 'post', '123')
    *       string   $format   - requested data format, e.g. 'xml' or 'json', or NULL
    *       object   $params   - parameters, e.g. (object) array('id' => '123', 'category' => 'sth')
    * 
    * For example
    *    foo/bar.json?id=123&category=sth
    * will produce
    *    array(array('foo', 'bar'), 'json', (object) array('id' => '123', 'category' => 'sth'))
    * 
    * and
    *    foo/bar/
    * will produce
    *    array(array('foo', 'bar'), null, (object) array())
    */
   
   private static function parseRequestURL($url)
   {
      // query string (url?query_string)
      
      $params = array();
      
      if(preg_match('/^(.*)\?(.+)$/', $url, $matches))
      {
         list(,$url, $queryString) = $matches;
         
         $queryString = explode('&', $queryString);
         
         foreach($queryString as $param)
         {
            list($key, $val) = explode('=', $param);
            
            $params[$key] = $val;
         }
      }
      
      // format (url.x)
      
      $format = null;
      
      if(preg_match('/^(.+)\.(.+)$/', $url, $matches))
      {
         list(, $url, $format) = $matches;
      }
      
      // splitting to segments
      
      $segments = array();
      
      foreach(explode('/', $url) as $segment)
      {
         if($segment !== '')
         {
            $segments[] = $segment;
         }
      }
      
      // returning
      
      return array($segments, $format, (object) $params);
   }
   
   /**************************************************************************/
   
   /*
    * private static array controllerDetails(string $controllerName, enum $type)
    * 
    * Returns controller details - path, and module name it belongs to
    * 
    * Returned data is in format: array($path, $bundleName)
    * 
    * Used by ::loadController()
    * 
    * enum $type = {self::Site, self::Admin}
    */
   
   private static function controllerDetails($controllerName, $type = self::Site)
   {
      // determining file extension and modulesList property name depending on $type
      
      if($type == self::Admin)
      {
         $key = 'acpcontrollers';
         $extension = '.acp.controller.php';
      }
      else
      {
         $key = 'controllers';
         $extension = '.controller.php';
      }
      
      // checking existence
      
      if(!isset(self::$config->modulesList->{$key}[$controllerName]))
      {
         return false;
      }
      
      // composing data
      
      $bundleName = self::$config->modulesList->{$key}[$controllerName];
      
      $path = BundlesPath . $bundleName . '/' . $controllerName . $extension;
      
      return array($path, $bundleName);
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
      self::$actionName     = strtolower(self::$segments[1]);
      
      // shortcuts
      
      $segments   = &self::$segments;
      $controller = &self::$controllerName;
      $action     = &self::$actionName;
      
      // controllers configuration
      
      $controllerHandler = 'pages';
      
      $useControllerHandler = false;
      $useDefaultController = false;
      
      // default controller
      
      $appType = self::$appType;
      
      if($appType == self::Admin)
      {
         $defaultController = 'blog';
      }
      else
      {
         $defaultController = self::$config->defaultController;
      }
      
      // determining controller to load
      
      if(empty($segments))
      {
         $controller = $defaultController;
         
         $useDefaultController = true;
      }
      else
      {
         // check if controller exists in modules list
         
         $controllerDetails = self::controllerDetails($controller, $appType);
         
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
         $controllerDetails = self::controllerDetails($controller, $appType);
         
         if($controllerDetails == false)
         {
            self::displayNoPageFoundError();
         }
      }
      
      // loading controller
      
      list($controllerPath, $bundleName) = $controllerDetails;
      
      include $controllerPath;
      
      $controllerClassName = $controller . '_Controller';
      $controllerObj       = new $controllerClassName;
      
      $controllerObj->bundleName = $bundleName;
      
      self::$controller = $controllerObj;
      
      /// if controller handler is set, run it
      
      if($useControllerHandler)
      {
         // note that currently controller handler is hardcoded - pages controller
         // in future probably it will be somehow changed
         
         CallMethodQuietly($controllerObj, '_controllerHandler', array(implode('/', $segments)));
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
}