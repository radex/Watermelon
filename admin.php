<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2008-2009 Radosław Pietruszewski.
 //  
 //  This program is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  This program is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 //  

$_w_startTime = microtime();
$_w_startTime = explode(' ', $_w_startTime);
$_w_startTime_msec = substr($_w_startTime[0],2);
$_w_startTime_sec  = $_w_startTime[1];
$_w_startTime = $_w_startTime_sec . $_w_startTime_msec;
$_w_startTime = substr($_w_startTime, 0, -2);

session_start();
ob_start();
session_regenerate_id();

header("Content-Type: text/html; charset=UTF-8");

## 
## magic quotes - odwracamy skutki działań magic quotes
## 

if(get_magic_quotes_gpc())
{
   function stripslashes_deep($value)
   {
      $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
      return $value;
   }
   $_POST = array_map('stripslashes_deep', $_POST);
   $_GET = array_map('stripslashes_deep', $_GET);
   $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
   $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

## 
## wczytujemy plik z konfiguracją
## 

if(file_exists('config.php'))
{
   include 'config.php';
   
   if(!isset($_w_baseURL))
   {
      header('Location: wtrmln/install/index.php');
      exit;
   }
}
else
{
   header('Location: wtrmln/install/index.php');
   exit;
}

## 
## definiowanie podstawowych stałych
## 

$_w_basePath = str_replace('\\', '/', realpath(dirname(__FILE__))) . '/';

define('WTRMLN_BASEURL'      , $_w_baseURL                                    );
define('WTRMLN_MAINURL'      , $_w_siteURL                                    );
define('WTRMLN_SITEURL'      , $_w_adminURL                                   );
define('WTRMLN_CMSDIR'       , $_w_cmsDir                                     );
define('WTRMLN_THEME'        , $_w_adminTheme                                 );
define('WTRMLN_DEFAULTCNT'   , $_w_PAdCnt                                     );
define('WTRMLN_SITENAME'     , $_w_siteName                                   );
define('WTRMLN_SITESLOGAN'   , $_w_siteSlogan                                 );

define('WTRMLN_CMSURL'       , $_w_baseURL    . WTRMLN_CMSDIR                 );
define('WTRMLN_CMSPATH'      , $_w_basePath   . WTRMLN_CMSDIR                 );
define('WTRMLN_APPPATH'      , WTRMLN_CMSPATH . 'modules/'                    );

define('WTRMLN_THEMEURL'     , WTRMLN_CMSURL  . 'themes_admin/' . WTRMLN_THEME . '/');
define('WTRMLN_THEMEPATH'    , WTRMLN_CMSPATH . 'themes_admin/' . WTRMLN_THEME . '/');
define('WTRMLN_LIBS'         , WTRMLN_CMSPATH . 'libs/'                       );
define('WTRMLN_ADMINLIBS'    , WTRMLN_CMSPATH . 'admin/libs/'                 );
define('WTRMLN_HELPERS'      , WTRMLN_CMSPATH . 'helpers/'                    );
define('WTRMLN_CACHE'        , WTRMLN_CMSPATH . 'cache/'                      );
define('WTRMLN_FILES'        , WTRMLN_CMSURL  . 'files/'                      );
define('WTRMLN_ACINFO'       , WTRMLN_APPPATH . 'acinfo/'                     );
define('WTRMLN_CONTROLLERS'  , WTRMLN_APPPATH . 'admin/'                      );
define('WTRMLN_VIEWS'        , WTRMLN_APPPATH . 'views/'                      );
define('WTRMLN_MODELS'       , WTRMLN_APPPATH . 'models/'                     );
define('WTRMLN_BLOCKS'       , WTRMLN_APPPATH . 'blocks/'                     );
define('WTRMLN_PLUGINS'      , WTRMLN_APPPATH . 'plugins/'                    );

define('ADMIN_MODE', '');

include WTRMLN_LIBS . 'config.php';

Config::$theme               = $_w_adminTheme;
Config::$defaultController   = $_w_PAdCnt;
Config::$hashAlgo            = $_w_hashAlgo;
Config::$defaultHashAlgo     = $_w_dHashAlgo;
Config::$siteName            = $_w_siteName;
Config::$siteSlogan          = $_w_siteSlogan;
Config::setSuperusers($_w_superusers);

## 
## odpalamy główny plik CMS-a
## 

include WTRMLN_CMSPATH . 'system_admin.php';

?>