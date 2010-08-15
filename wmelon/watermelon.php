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
      
      DB::connect();
      URI::divide();
      
      //var_dump(URI::$appType, URI::$segments);
      
      
      // to do
      
      $content = '<strong>Test!</strong>';
      
      self::generate($content);
      
      //--
      
      UnitTester::runTests();
      UnitTester::printFails();
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
      
      // setting constants
      
      define('WM_CMSPATH',       $_w_basePath . $_w_cmsDir . '/');
      define('WM_LIBS',          WM_CMSPATH . 'libs/');
      define('WM_HELPERS',       WM_CMSPATH . 'helpers/');
      define('WM_TESTS',         WM_CMSPATH . 'tests/');
      
      // loading libraries and helpers
      
      include WM_LIBS    . 'libs.php';
      include WM_HELPERS . 'helpers.php';
      
      // config
      
      Registry::create('wmelon.db.config', $dbConfig, false, 'DB');
   }
   
   /*
    * private static void generatePage(string $content)
    */
   
   private static function generate($content)
   {
      // replacing made in simple manner links into HTML
      
      // $content = str_replace('href="$/',   'href="'   . WM_SITEURL, $content);
      // $content = str_replace('action="$/', 'action="' . WM_SITEURL, $content);
      
      define('WM_SkinPath', 'wm-public/skins/wcmslay/');
      define('WM_THEMEURL', 'http://localhost/w/wm-public/skins/wcmslay/');
      
      include WM_SkinPath . 'skin.php';
      
      self::$headData = '<title>Test!</title>';
      
      $skin = new WCMSLay_skin($content, self::$headData);
      
      $skin->display();
   }
}