<?php
########################################
define('WTRMLN_IS','true');#############
$_w_autoload=array();###################
########################################
#              Konfiguracja            #
########################################
$_w_baseURL    = 'http://localhost/watermeloncms/';
$_w_siteURL    = 'http://localhost/watermeloncms/';
$_w_adminURL   = 'http://localhost/watermeloncms/admin.php/';
$_w_cmsDir     = 'wtrmln/';
$_w_dbHost     = 'localhost';
$_w_dbUser     = 'root';
$_w_dbPass     = '';
$_w_dbName     = 'watermelon';
$_w_dbPrefix   = 'wcms_';
$_w_siteName   = 'Watermelon CMS';
$_w_siteSlogan = '... bo arbuzy dobre są ...';
$_w_theme      = 'wcmslay';
$_w_adminTheme = 'wcmslay';
$_w_defaultCnt = 'test';
$_w_PAdCnt     = 'test';
$_w_hashAlgo   = array('xsha256');
$_w_dHashAlgo  = 0;
$_w_autoload   = array(array('user', ''), array('benchmark', ''));
$_w_metaSrc    = array('');
$_w_superusers = array('radex');

########################################
#              Zaawansowane            #
########################################

//error_reporting(0);
  error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);
//error_reporting(E_ALL | E_STRICT);

//define('NOMENU'        , ''   );
  define('DEBUG'         , ''   );
//define('CACHE_VIEWS'   , ''   );
  define('BENCHMARK_SITE', false);
  define('DEBUG_FOOTER'  , ''   );
?>
