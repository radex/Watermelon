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

define('WM_DEBUGLEVEL', '1') // 0 - no debug notices, no error reporting; real world applications
                             // 1 - debug notices, E_ALL ^ E_NOTICE error reporting; programming
                             // 2 - debug notices, E_ALL error reporting; testing & debugging

//define('WM_BENCHMARK', '');

/*   Users   */

$_w_hashAlgo   = 'sha256';
$_w_superusers = array('radex');

/*   Caching   */

//define('CACHE_VIEWS'   , ''   );
//define('CACHE_BBCODE'  , ''   );

/*   Other   */

$_w_autoload   = array(array('user', ''), array('benchmark', ''));

?>
