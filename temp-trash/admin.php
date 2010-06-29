<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2009 Radosław Pietruszewski.
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

define('WM_BASEURL'      , $_w_baseURL                                    );
define('WM_MAINURL'      , $_w_siteURL                                    );
define('WM_SITEURL'      , $_w_adminURL                                   );
define('WM_CMSDIR'       , $_w_cmsDir                                     );
define('WM_THEME'        , $_w_adminTheme                                 );
define('WM_DEFAULTCNT'   , $_w_PAdCnt                                     );
define('WM_SITENAME'     , $_w_siteName                                   );
define('WM_SITESLOGAN'   , $_w_siteSlogan                                 );

define('WM_CMSURL'       , $_w_baseURL    . WM_CMSDIR                 );
define('WM_CMSPATH'      , $_w_basePath   . WM_CMSDIR                 );
define('WM_APPPATH'      , WM_CMSPATH . 'modules/'                    );

define('WM_THEMEURL'     , WM_CMSURL  . 'themes_admin/' . WM_THEME . '/');
define('WM_THEMEPATH'    , WM_CMSPATH . 'themes_admin/' . WM_THEME . '/');
define('WM_LIBS'         , WM_CMSPATH . 'libs/'                       );
define('WM_ADMINLIBS'    , WM_CMSPATH . 'admin/libs/'                 );
define('WM_HELPERS'      , WM_CMSPATH . 'helpers/'                    );
define('WM_CACHE'        , WM_CMSPATH . 'cache/'                      );
define('WM_FILES'        , WM_CMSURL  . 'files/'                      );
define('WM_ACINFO'       , WM_APPPATH . 'acinfo/'                     );
define('WM_CONTROLLERS'  , WM_APPPATH . 'admin/'                      );
define('WM_VIEWS'        , WM_APPPATH . 'views/'                      );
define('WM_MODELS'       , WM_APPPATH . 'models/'                     );
define('WM_BLOCKS'       , WM_APPPATH . 'blocks/'                     );
define('WM_PLUGINS'      , WM_APPPATH . 'plugins/'                    );

define('ADMIN_MODE', '');

include WM_LIBS . 'config.php';

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

include WM_CMSPATH . 'system_admin.php';

?>