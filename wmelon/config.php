<?php
 //  
 //  config.php
 //  Watermelon CMS
 // 

                                                      // TODO: Write config documentation

/*   URL-s   */

$_w_baseURL     = 'http://localhost/w/';
$_w_siteURL     = 'http://localhost/w/';

/*   Database   */

$_w_dbHost      = 'localhost';
$_w_dbUser      = 'watermeloner';
$_w_dbPass      = 'wtrmln123';
$_w_dbName      = 'watermelon';
$_w_dbPrefix    = '';

/****************\
     ADVANCED
\****************/

/*   Folders   */

$_w_publicDir   = 'wm-public';
$_w_uploadedDir = 'wm-uploaded';

/*   Error reporting and debugging   */

//error_reporting(0);                     // real world applications
  error_reporting(E_ALL ^ E_NOTICE);      // programming
//error_reporting(E_ALL);                 // testing, debugging

  define('DEBUG'         , '');
  define('BENCHMARK_SITE', '');
  define('DEBUG_FOOTER'  , '');

/*   Users   */

$_w_hashAlgo   = 'sha256';
$_w_superusers = array('radex');

/*   Caching   */

//define('CACHE_VIEWS'   , ''   );
  define('CACHE_BBCODE'  , ''   );

/*   Other   */

$_w_autoload   = array(array('user', ''), array('benchmark', ''));

?>
