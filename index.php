<?php

$_w_cmsDir = 'wmelon'; // main CMS directory

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

define('WM_IS','');

// saving time when starting executing a script for a benchmark

$_w_startTime = explode(' ', microtime());
$_w_startTime = substr($_w_startTime[1] . substr($_w_startTime[0],2), 0, -2);

// loading configuration

include $_w_cmsDir . '/config.php';

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