<?php
 //  
 //  index.php
 //  Watermelon CMS
 //  
 //  Copyright 2008-2010 Radosław Pietruszewski.
 //

define('WM_IS','');

$_w_cmsDir = 'wmelon'; // main CMS folder

// saving time when starting executing a script for a benchmark

$_w_startTime = explode(' ', microtime());
$_w_startTime = substr($_w_startTime[1] . substr($_w_startTime[0],2), 0, -2);

// loading configuration

@include $_w_cmsDir . '/config.php';

if(!isset($_w_baseURL))
{
   header('Location: wm-installer/index.php');
   exit;
}

// defining base path

$_w_basePath = str_replace('\\', '/', realpath(dirname(__FILE__))) . '/';

// running main CMS file

include $_w_basePath . $_w_cmsDir . '/watermelon.php';

?>