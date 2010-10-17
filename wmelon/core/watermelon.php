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
    *    ::AppType_Site      - for website
    *    ::AppType_Admin     - for admin control panel
    *    ::AppType_Installer - for Watermelon installer
    */
   
   public static $appType;
   
   const AppType_Site      = 1;
   const AppType_Admin     = 2;
   const AppType_Installer = 3;
   
   /*
    * public static string[] $segments
    * 
    * Array of resource identificator segments, stripped from controller and action name
    */
   
   public static $segments = array();
   
   /*
    * public static string $packageName
    * 
    * Name of package currently running controller belongs to
    */
   
   public static $packageName = '';
   
   /*
    * public static string $controllerName
    * 
    * Name of currently running controller
    */
   
   public static $controllerName = '';
   
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
    * autoload          - array of extension names to load automatically
    * controllerHandler - controller to run if there's no controller with name such as in URI
    * defaultController - controller to run if no controller name is given in URI
    * 
    * siteURL           - base URL for website pages
    * systemURL         - base URL for wmelon/ contents
    * 
    * skin              - website skin name
    * lang              - website language code
    * 
    * siteName          - website name
    * siteSlogan        - website slogan (some text, usually placed below site name; shown in some skins)
    * footer            - additional text (HTML) to put in footer
    * blockMenus        - array of block-based menus (structure described in Skin class)
    * textMenus         - array of text-based menus ( -||- )
    * 
    * ---
    * ModulesList structure:
    * 
    * 
    * $modulesList->controllers/models/blocksets/extensions =
    *    array($name => $info)
    *       $name - module name
    *       $info = array(string $package, bool $inDir)
    *          string $package - package, $name module belongs to
    *          bool   $inDir   - whether module is in root of package directory (false) or in separate directory, e.g. controllers/ (true)
    * 
    * $moduleList->skins =
    *    array(string $package, ...)
    *       string $package - name of package with a skin
    */
   
   public static $config;
   
   /*
    * controller object
    */
   
   private static $controllerObject;
   
   /*
    * public static void addMessage(string $type, string $message)
    * 
    * Adds message to be displayed on next page generation
    * 
    * string $type    - type of message (error, warning, info, tip, tick)
    * string $message - actual message string
    * 
    * Note that if you stop the script (by refreshing or redirecting), messages in array will be displayed on the page after refreshing/redirecting
    */
   
   public static function addMessage($type, $message)
   {
      $type    = (string) $type;
      $message = (string) $message;
      
      $_SESSION['WM_Messages'][] = array($type, $message);
   }
   
   /*
    * public static void displayNoPageFoundError()
    * 
    * Loads 'e404' controller ("no page found" page)
    */
   
   public static function displayNoPageFoundError()
   {
      include WM_Packages . 'watermelon/e404.controller.php';
      
      self::$packageName = 'watermelon';
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
      
      // if installer - skipping configuration etc, just loading controller and generating
      
      if(self::$appType == self::AppType_Installer)
      {
         include WM_Packages . 'installer/installer.controller.php';
         
         $installer = new Installer_Controller;
         
         self::$controllerObject = $installer;
         self::$controllerName   = 'installer';
         self::$packageName      = 'installer';
         
         $installer->installer();
         
         self::generate();
         
         return;
      }
      
      //--
      
      self::config();
      
      // auto-loading extensions
      
      foreach(self::$config->autoload as $extensionName)
      {
         Loader::extension($extensionName);
         
         $className = $extensionName . '_Extension';
         $className::onAutoload();
      }
      
      include WM_Core . 'FrontendLibraries/FrontendLibraries.extension.php';
      FrontendLibraries_Extension::onAutoload();
      
      include WM_Core . 'Textile/textile.extension.php';
      Textile_Extension::onAutoload();
      
      //--
      
      self::loadController();
      
      // tests
      
      /*
      include WM_Libs . 'Registry/Registry.test.php';
      UnitTester::runTest(new Registry_TestCase);
      */
      
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
      
      if(!is_array($_SESSION['WM_Messages']))
      {
         $_SESSION['WM_Messages'] = array();
      }
      
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
      
      define('WM_BasePath', $basePath);
      define('WM_Core',     WM_BasePath . 'core/');
      define('WM_Packages', WM_BasePath . 'packages/');
      define('WM_Uploaded', WM_BasePath . 'uploaded/');
      define('WM_Cache',    WM_BasePath . 'cache/');
      
      define('WM_Libs',     WM_Core . 'libs/');
      define('WM_Helpers',  WM_Core . 'helpers/');
      
      // loading config file, and setting app type to installer if empty
      
      include $basePath . 'config.php';
      
      if(!isset($dbHost))
      {
         self::$appType = self::AppType_Installer;
         
         error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE); // TODO: delete it later
         
         include WM_Libs . 'libs.php';
         include WM_Helpers . 'helpers.php';
         
         self::divide();
         
         return;
      }
      
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
      
      // loading libraries and helpers
      
      include WM_Libs . 'libs.php';
      include WM_Helpers . 'helpers.php';
      
      // running DB, and dividing URL
      
      DB::connect($dbHost, $dbName, $dbUser, $dbPass, $dbPrefix);
      
      self::divide();
   }
   
   /*
    * Auxiliary method of indexModules() method. Searches for concrete module files in specified path, and adds them to modulesList
    */
   
   private static function modulesInDirectory($path, $packageName, $moduleType, $inDir, $modulesList)
   {
      $files = FilesForDirectory($path, false, true);
      
      foreach($files as $file)
      {
         $ext = '.' . $moduleType . '.php';
         $extLen = strlen($ext);
         
         if(substr($file->getFilename(), -$extLen) != $ext)
         {
            continue;
         }
         
         $modulesList->{$moduleType . 's'}[substr($file->getFilename(), 0, -$extLen)] = array($packageName, $inDir);
      }
   }
   
   /*
    * searches packages for module files - controllers, models, extensions, etc., in order to create modules list
    */
   
   private static function indexModules()
   {
      $modulesList = new stdClass;
      
      $modulesList->controllers = array();
      $modulesList->models      = array();
      $modulesList->blocksets   = array();
      $modulesList->extensions  = array();
      $modulesList->skins       = array();
      
      foreach(new DirectoryIterator(WM_Packages) as $dir)
      {
         // if not a package, or Installer package
         
         if(!$dir->isDir() || $dir->isDot() || $dir->getFilename() == 'installer')
         {
            continue;
         }
         
         $packageName = $dir->getFilename();
         
         // skins
         
         if(substr($package, 0, -5) == '_skin' && file_exists(WM_Packages . $packageName . '/skin.php'))
         {
            $modulesList->skins[] = $packageName;
         }
         
         // controllers, models, blocksets and extensions
         
         $moduleTypes = array('controller', 'model', 'blockset', 'extension');
         
         foreach($moduleTypes as $moduleType)
         {
            // module in root of the package
            
            self::modulesInDirectory(WM_Packages . $packageName, $packageName, $moduleType, false, $modulesList);
            
            // module in [type]s/ directory of the package
            
            $subdirPath = WM_Packages . $packageName . '/' . $moduleType . 's/';
            
            if(file_exists($subdirPath))
            {
               self::modulesInDirectory($subdirPath, $packageName, $moduleType, true, $modulesList);
            }
         }
      }
      
      return $modulesList;
   }
   
   /*
    * private static void config()
    * 
    * Loads configuration, sets URL constants, etc
    */
   
   private static function config()
   {
      // modules
      
      $w->modulesList       = self::indexModules();            // TODO: only in debug
      $w->autoload          = array('auth', 'comments');
      $w->controllerHandler = null;
      $w->defaultController = 'test';
      
      // other
      
      $w->siteURL           = 'http://localhost/w/index.php/';
      $w->systemURL         = 'http://localhost/w/wmelon/';
      
      $w->skin              = 'wcmslay';
      $w->lang              = 'pl';
      
      // frontend
      
      $textMenus = array(array
         (
            array('Blog', 'blog', false, 'Blooog!!!'),
            array('Testy', 'test', false, null),
            array('Login', 'auth/login', false, null),
            array('Logout', 'auth/logout', false, null),
         ));
      
      $blockMenus = array(array
         (
            //array('Test::foo', 'test', 'foo', array()),
            array('Test!', 'user', 'card', array()),
         ));
      
      $w->siteName   = 'Nazwa strony';
      $w->siteSlogan = 'Slogan strony';
      $w->footer     = 'Testowanie <em>stopki</em>…';
      $w->blockMenus = $blockMenus;
      $w->textMenus  = $textMenus;
      
      // setting config
      
      Registry::create('wmelon', $w, true);
      Registry::set('wmelon', $w);           // only for development
      
      // $w = Registry::get('wmelon'); // after development
      
      self::$config = &$w;
      
      // setting constants
      
      define('WM_SiteURL',     $w->siteURL);
      define('WM_SystemURL',   $w->systemURL);
      define('WM_PackagesURL', WM_SystemURL . 'packages/');
      define('WM_UploadedURL', WM_SystemURL . 'uploaded/');
      
      define('WM_SkinPath', WM_Packages    . $w->skin . '_skin/');
      define('WM_SkinURL',  WM_PackagesURL . $w->skin . '_skin/');
      
      define('WM_Lang', $w->lang);
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
      
      // setting app type (but only if not already set to installer)
      
      if(self::$appType != self::AppType_Installer)
      {
         if($segments[0] == 'admin')
         {
            self::$appType = self::AppType_Admin;
         
            array_shift($segments);
         }
         else
         {
            self::$appType = self::AppType_Site;
         }
      }
      
      //--
      
      self::$segments = $segments;
   }
   
   /*
    * private static array controllerDetails(string $controllerName)
    * 
    * Returns controller details - path, and module name it belongs to
    * 
    * Returned data is in format: array($path, $packageName)
    * 
    * Used by ::loadController()
    */
   
   private static function controllerDetails($controllerName)
   {
      if(!isset(self::$config->modulesList->controllers[$controllerName]))
      {
         return false;
      }
      
      $info  = self::$config->modulesList->controllers[$controllerName];
      $path  = WM_Packages . $info[0] . ($info[1] == true ? '/controllers/' : '/') . $controllerName . '.controller.php';
      
      return array($path, $info[0]);
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
      
      $controllerHandler = self::$config->controllerHandler;
      $defaultController = self::$config->defaultController;
      
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
         
         $controllerDetails = list($controllerPath, self::$packageName) = self::controllerDetails($controller);
         
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
         $controllerDetails = list($controllerPath, self::$packageName) = self::controllerDetails($controller);
         
         if($controllerDetails == false)
         {
            self::displayNoPageFoundError();
         }
      }
      
      // loading controller
      
      include $controllerPath;
      
      $controllerClassName = $controller . '_Controller';
      $controllerObj       = new $controllerClassName;
      
      self::$controllerObject = $controllerObj;
      
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
    * private static void generatePage()
    * 
    * Generates page (loads skin etc)
    */
   
   private static function generate()
   {
      $content = ob_get_clean();
      
      // replacing made in simple manner links into HTML
      
      $content = str_replace('href="$/',   'href="'   . WM_SiteURL, $content);
      $content = str_replace('action="$/', 'action="' . WM_SiteURL, $content);
      
      // running skin, or outputing data
      
      $controller = self::$controllerObject;
      $outputType = $controller->requestedOutputType;
      
      if($outputType == Controller::Plain_OutputType)
      {
         echo $content;
      }
      elseif($outputType == Controller::XML_OutputType)
      {
         header('Content-Type: text/xml');
         
         echo $controller->output->asXML();
      }
      else
      {
         include WM_SkinPath . 'skin.php';

         $className = self::$config->skin . '_skin';

         $skin = new $className;

         $skin->content    = &$content;
         
         $messages = $_SESSION['WM_Messages'];
         $_SESSION['WM_Messages'] = array();
         
         $skin->headTags   = &self::$headTags;
         $skin->tailTags   = &self::$tailTags;
         $skin->messages   = &$messages;
         $skin->pageTitle  = $controller->pageTitle;
         $skin->siteName   = &self::$config->siteName;
         $skin->siteSlogan = &self::$config->siteSlogan;
         $skin->footer     = &self::$config->footer;
         $skin->blockMenus = &self::$config->blockMenus;
         $skin->textMenus  = &self::$config->textMenus;
         $skin->additionalData = $controller->additionalData;

         $skin->display();
      }
   }
}