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
    * public void __construct()
    * 
    * Constructor. Loads libraries, proper controller and generates a page
    */
   
   public function __construct()
   {
      $this->prepare();
      
      DB::connect();
      
      self::$headData = $headData;
      
      // to do
   }
   
   private function prepare()
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
      
      // setting proper error reporting modes, and debug constants in respect to internal debug level constant

      switch(WM_DEBUGLEVEL)
      {
         case'0':
         default:
            error_reporting(0);
            break;
         case '1':
            error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);
            define('WM_DEBUG', '');
            break;
         case '2':
            error_reporting(E_ALL);
            define('WM_DEBUG', '');
            break;
      }
      
      // importing some variables from global scope
      
      global $_w_baseURL, $_w_siteURL, $_w_publicDir, $_w_uploadedDir, $_w_cmsDir, $_w_basePath;
      global $_w_dbHost, $_w_dbUser, $_w_dbPass, $_w_dbName, $_w_dbPrefix;
      global $_w_superuser, $_w_hashAlgo, $_w_startTime;
      
      // setting constants
      
      // TODO: change to class constants

      define(    'WM_SITEURL', $_w_siteURL                         );
      define(     'WM_PUBURL', $_w_baseURL  . $_w_publicDir   . '/');
      define('WM_UPLOADEDURL', $_w_baseURL  . $_w_uploadedDir . '/');

      define(    'WM_CMSPATH', $_w_basePath . $_w_cmsDir      . '/');
      define(    'WM_PUBPATH', $_w_basePath . $_w_publicDir   . '/');

      define(       'WM_LIBS', WM_CMSPATH . 'libs/');
      define(    'WM_HELPERS', WM_CMSPATH . 'helpers/');
      define(      'WM_TESTS', WM_CMSPATH . 'tests/');
      
      // loading libraries and helpers

      include WM_LIBS    . 'libs.php';
      include WM_HELPERS . 'helpers.php';

      // config

      Registry::create('hashAlgo',  $_w_hashAlgo,  false, true);
      Registry::create('superuser', $_w_superuser, false, true);

      $_w_dbConfig = array
         (
            'host'   => $_w_dbHost,
            'user'   => $_w_dbUser,
            'pass'   => $_w_dbPass,
            'name'   => $_w_dbName,
            'prefix' => $_w_dbPrefix
         );

      Registry::create('wmelon.db.config',  $_w_dbConfig,  false, 'DB');

      // unsetting database configuration data (for safety)

      unset($_w_dbHost);
      unset($_w_dbUser);
      unset($_w_dbPass);
      unset($_w_dbName);
      unset($_w_dbPrefix);
   }
   
   /*
    * private void generatePage(string $content)
    */
   
   private function generatePage($content)
   {
      // replacing made in simple manner links into HTML
      
      $content = str_replace('href="$/',   'href="'   . WM_SITEURL, $content);
      $content = str_replace('action="$/', 'action="' . WM_SITEURL, $content);
      
      //to do...
   }
}