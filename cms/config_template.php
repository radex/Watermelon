<?php
/********************************************************************

  Watermelon CMS

Copyright 2008 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

define('WTRMLN_IS','true');
$_w_autoload = array();
$_w_superusers = array();

########################################
#              Konfiguracja            #
########################################

// ścieżki

$_w_baseURL    = '';
$_w_siteURL    = '';
$_w_cmsDir     = 'wtrmln/';
$_w_appDir     = 'modules/';

// baza danych

$_w_dbHost     = '';
$_w_dbUser     = '';
$_w_dbPass     = '';
$_w_dbName     = '';
$_w_dbPrefix   = '';

// inne

$_w_siteName   = '';
$_w_siteSlogan = '';
$_w_theme      = '';
$_w_defaultCnt = '';
$_w_hashAlgo   = array(' md5');

// autoload

$_w_autoload[] = array('user', '');

// head

$_w_metaSrc =
	array(

	);

$_w_superusers[''] = '';

########################################
#      Systemowe -  NIE EDYTOWAĆ!      #
########################################

$_w_basePath = str_replace('\\','/',realpath(dirname(__FILE__))).'/';

define('WTRMLN_BASEURL'      , $_w_baseURL                                  );
define('WTRMLN_SITEURL'      , $_w_siteURL                                  );
define('WTRMLN_CMSDIR'       , $_w_cmsDir                                   );
define('WTRMLN_APPDIR'       , $_w_appDir                                   );
define('WTRMLN_THEME'        , $_w_theme                                    );
define('WTRMLN_DEFAULTCNT'   , $_w_defaultCnt                               );
define('WTRMLN_HASHALGO'     , serialize($_w_hashAlgo)                      );
define('WTRMLN_SITENAME'     , $_w_siteName                                 );
define('WTRMLN_SITESLOGAN'   , $_w_siteSlogan                               );
define('WTRMLN_BASEPATH'     , $_w_basePath                                 );

define('WTRMLN_CMSURL'       , WTRMLN_BASEURL.WTRMLN_CMSDIR                 );
define('WTRMLN_APPURL'       , WTRMLN_CMSURL.WTRMLN_APPDIR                  );
define('WTRMLN_CMSPATH'      , WTRMLN_BASEPATH.WTRMLN_CMSDIR                );
define('WTRMLN_APPPATH'      , WTRMLN_CMSPATH.WTRMLN_APPDIR                 );

define('WTRMLN_THEMEURL'     , WTRMLN_CMSURL.'themes/'.WTRMLN_THEME.'/'     );
define('WTRMLN_THEMEPATH'    , WTRMLN_CMSPATH.'themes/'.WTRMLN_THEME.'/'    );
define('WTRMLN_LIBS'         , WTRMLN_CMSPATH.'libs/'                       );
define('WTRMLN_ADMINLIBS'    , WTRMLN_CMSPATH.'admin/libs/'                 );
define('WTRMLN_HELPERS'      , WTRMLN_CMSPATH.'helpers/'                    );
define('WTRMLN_FILES'        , WTRMLN_CMSURL.'files/'                       );
define('WTRMLN_ADMIN'        , WTRMLN_CMSURL.'admin/'                       );
define('WTRMLN_ADMINCNT'     , WTRMLN_APPPATH.'admin/'                      );
define('WTRMLN_CONTROLLERS'  , WTRMLN_APPPATH.'controllers/'                );
define('WTRMLN_VIEWS'        , WTRMLN_APPPATH.'views/'                      );
define('WTRMLN_MODELS'       , WTRMLN_APPPATH.'models/'                     );
define('WTRMLN_PLUGINS'      , WTRMLN_APPPATH.'plugins/'                    );

?>
